<?php

namespace App\Http\Controllers\Payments;

use App\Models\CheckoutSession;
use App\Models\PaymentGateway;
use App\Models\PaymentProvider;
use Hashids;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Listing;
use App\Http\Requests\UpdateUserProfile;
use Image;
use Storage;
use GeoIP;
use Date;
use URL;
use App\Support\PaypalClassic;
use Socialite;
use App\Events\OrderPlaced;
use Carbon\Carbon;
use Mail;
use App\Mail\AcceptPurchase;


/*
 * Methods
 *  index -> shows the payment page or redirects
 *  store -> processes the payment and redirects to the purchase history
 *
 */

class PaypalSplitController extends BaseController
{
    public function accept($order) {
		return true;
        $paypal = new PaypalClassic();
        $paypal_params = [
            'TOKEN'     => $order->authorization_id,
        ];
        $paypal->setParams($paypal_params);
        $response = $paypal->send('GetExpressCheckoutDetails');
		#dd($response);

        $capture_params = [
            'cancelUrl' => route('payments.paypal-split.cancel', ['listing' => $order->listing->id],true),
            'returnUrl' => route('payments.paypal-split.callback', [], true),
            'AMT'               => $response['AMT'],
            'PAYERID'           => $response['PAYERID'],
            'TOKEN'             => $order->authorization_id,
            'PAYMENTREQUEST_0_PAYMENTACTION' => 'Sale',
            'PAYMENTREQUEST_0_SELLERPAYPALACCOUNTID' => $response['PAYMENTREQUEST_0_SELLERPAYPALACCOUNTID'],
            'PAYMENTREQUEST_0_AMT' => (float) $response['PAYMENTREQUEST_0_AMT'],
            'PAYMENTREQUEST_0_PAYMENTREQUESTID' => 'SELLER',
            'PAYMENTREQUEST_0_CURRENCYCODE' => $response['PAYMENTREQUEST_0_CURRENCYCODE'],
            'PAYMENTREQUEST_0_DESC' => $response['PAYMENTREQUEST_0_DESC'],
            'PAYMENTREQUEST_0_CUSTOM' => (int) $response['PAYMENTREQUEST_0_CUSTOM'],
        ];

        if($response['PAYMENTREQUEST_1_AMT']) {
            $capture_params += [
                'PAYMENTREQUEST_1_PAYMENTACTION' => 'Sale',
                'PAYMENTREQUEST_1_SELLERPAYPALACCOUNTID' => $response['PAYMENTREQUEST_1_SELLERPAYPALACCOUNTID'],
                'PAYMENTREQUEST_1_AMT' => (float) $response['PAYMENTREQUEST_1_AMT'],
                'PAYMENTREQUEST_1_PAYMENTREQUESTID' => 'MARKETPLACE',
                'PAYMENTREQUEST_1_CURRENCYCODE' => $response['PAYMENTREQUEST_1_CURRENCYCODE'],
                'PAYMENTREQUEST_1_DESC' => $response['PAYMENTREQUEST_1_DESC'],
                'PAYMENTREQUEST_1_CUSTOM' => (int)  $response['PAYMENTREQUEST_1_CUSTOM'],
            ];
        }

        $paypal->setParams($capture_params);
        $response = $paypal->send('DoExpressCheckoutPayment');
        if($response['ACK'] == 'Success') {
            $capture_id =  $response['PAYMENTINFO_0_TRANSACTIONID'];
            if(isset($response['PAYMENTINFO_1_TRANSACTIONID'])) {
                $capture_id .= "|".$response['PAYMENTINFO_1_TRANSACTIONID'];
            }
            return $capture_id;
        }

        return false;
    }
    public function decline($order) {
		#refund transaction
		#message please refund the user via the admin panel
		return true;
		        $paypal = new PaypalClassic();
        $paypal->setParams([
            'TOKEN' => $order->authorization_id
        ]);
        $response = $paypal->send('GetExpressCheckoutDetails');
		#dd( $response);
		$paypal = new PaypalClassic();
        $paypal_params = [
            'TRANSACTIONID'  => $response['PAYMENTREQUEST_0_TRANSACTIONID'],
            'AMT'  => $response['PAYMENTREQUEST_0_AMT'],
            'REFUNDTYPE'     => 'Partial',
			'SUBJECT' => $response['PAYMENTREQUEST_0_SELLERPAYPALACCOUNTID'],
        ];
        $paypal->setParams($paypal_params);
        $response = $paypal->send('RefundTransaction');
		dd($response);
		/*
		
		&PWD=merchant_password
&SIGNATURE=merchant_signature
&METHOD=RefundTransaction
&VERSION=94
&TRANSACTIONID=transaction_ID    #ID of the transaction for which the refund is made
&REFUNDTYPE=Full    #Specifies a full refund; a partial refund requires more input fields
*/
		
        return true;
    }

    public function index($session, Request $request)
    {
        $listing = $session->listing;
		$gateway = $listing->user->payment_gateway($session->payment_provider->key);
        if(!$gateway) {
            dd("NO SELLER ID");
        }

		#dd($session->payment_provider->extra_attributes['paypal_email']);
        #calculate the real price of the order
        $widget = '\App\Widgets\Order\\'.studly_case($listing->pricing_model->widget).'Widget';
        $widget = new $widget();
        $result = $widget->calculate_price($listing, $session->request);
		
		#dd($session->request['quantity']);
		
        $query_params = collect($session->request)->reject(function ($k, $v) {
            return substr( $v, 0, 3 ) === "ic-" || substr( $v, 0, 1 ) === "_";
        });
        $query_params['listing_id'] = $listing->id;
        $query_string = '';
        if($query_params)
            $query_string = http_build_query($query_params->toArray());

        $paypal_email = $gateway->gateway_id;
        $paypal = new PaypalClassic();
		$qty = $session->request['quantity']?$session->request['quantity']:1;
		$cancelUrl = route('payments.paypal-split.cancel', ['listing' => $listing->id],true);
		if(url()->previous())
			$cancelUrl = url()->previous();
		
		$name = $listing->title;
		if($result['price_items']) {
			foreach($result['price_items'] as $i => $price_item) {
				if($price_item['key'] == 'price') {
					$name = $listing->title.': '.$price_item['label'];
				}
				
			}
		}
		
        $paypal_params = [
            'cancelUrl' => $cancelUrl,
            'returnUrl' => route('payments.paypal-split.callback', [], true),
            'AMT'       => number_format($result['total'], 2),
            'BRANDNAME' => setting('site_title'),
            'PAYMENTREQUEST_0_PAYMENTACTION' => 'Sale',
            'PAYMENTREQUEST_0_NAME' => $name,
            'PAYMENTREQUEST_0_SELLERPAYPALACCOUNTID' => $paypal_email,
            'PAYMENTREQUEST_0_AMT' => number_format($result['total']-$result['service_fee'], 2, '.', ''),
            'PAYMENTREQUEST_0_PAYMENTREQUESTID' => 'SELLER',
            'PAYMENTREQUEST_0_CURRENCYCODE' => $listing->currency,
            #'L_PAYMENTREQUEST_0_AMT0' => (float) number_format(($result['total']-$result['service_fee'])/$qty, 2, '.', ''),
            #'L_PAYMENTREQUEST_0_QTY0' => $qty,
            'PAYMENTREQUEST_0_DESC' => $listing->title,
            'PAYMENTREQUEST_0_CUSTOM' => $session->id,
        ];
		
		/*if($result['price_items']) {
			foreach($result['price_items'] as $i => $price_item) {
				if($price_item['key'] == 'service') {
					continue;
				}	
				
				$qty = 1;
				$amt = $price_item['price'];
				$name = $price_item['label'];
				if($price_item['key'] == 'price') {
					$name = $listing->title;
					$amt = $listing->price;
					$qty = (float) $price_item['price']/$listing->price;
				}
				$paypal_params['L_PAYMENTREQUEST_0_NAME'.$i] = $name;
				$paypal_params['L_PAYMENTREQUEST_0_AMT'.$i] = $amt;
				$paypal_params['L_PAYMENTREQUEST_0_QTY'.$i] = $qty;
				
			}
		}*/
		#dd($result['service_fee']);
        if($result['service_fee']) {
            $paypal_params += [
                'PAYMENTREQUEST_1_PAYMENTACTION' => 'Sale',
                'PAYMENTREQUEST_1_SELLERPAYPALACCOUNTID' => $session->payment_provider->extra_attributes['paypal_email'],
                'PAYMENTREQUEST_1_AMT' => number_format($result['service_fee'], 2),
                'PAYMENTREQUEST_1_PAYMENTREQUESTID' => 'MARKETPLACE',
                'PAYMENTREQUEST_1_CURRENCYCODE' => $listing->currency,
                'PAYMENTREQUEST_1_DESC' => "Marketplace fee",
                'PAYMENTREQUEST_1_CUSTOM' => $session->id,
            ];
        }

        #dd($paypal_params);
        $paypal->setParams($paypal_params);
        $response = $paypal->send('SetExpressCheckout');
        if(isset($response['TOKEN'])) {
            $url = "https://www.paypal.com/checkoutnow?token=" . $response['TOKEN'];
            if (config('private.paypal_mode') == 'sandbox')
                $url = "https://www.sandbox.paypal.com/checkoutnow?token=" . $response['TOKEN'];
            return redirect($url);
        } else {
            dd($response);
        }

    }

    public function connect(Request $request)
    {
        #paypal oauth
		$url = "https://app.marketplacekit.com/paypal/start?tenant=".current_tenant().'&mode='.config('private.paypal_mode');
		return redirect($url);
    }

    public function connected(Request $request) {
		if($request->input('error')) {
			return redirect(route('account.bank-account.index'));
		}
        $driver = "paypal";
        if (config('private.paypal_mode') == 'sandbox')
            $driver = "paypal_sandbox";
		
		$redirect_url = 'https://app.marketplacekit.com/paypal/callback';
        if (config('private.paypal_mode') == 'sandbox') {
            config(['services.paypal_sandbox.client_id' => config('private.paypal_login_client_id')]);
            config(['services.paypal_sandbox.client_secret' => config('private.paypal_login_secret')]);
			config(['services.paypal_sandbox.redirect' => $redirect_url]);
        } else {
            config(['services.paypal.client_id' => config('private.paypal_login_client_id')]);
            config(['services.paypal.client_secret' => config('private.paypal_login_secret')]);
			config(['services.paypal.redirect' => $redirect_url]);
        }

        $paypal_user = Socialite::driver($driver)->stateless()->user();
        $payment_gateway = PaymentGateway::firstOrCreate([
            'name' => 'paypal_split',
            'gateway_id' => $paypal_user->email,
            'user_id' => auth()->user()->id
        ]);
		
        $user = auth()->user();
        $user->can_accept_payments = true;
        $user->save();

        alert()->success(__('You can now accept payments and start selling.'));

        return redirect(route('account.bank-account.index'));
    }

    public function store(Request $request)
    {
        dd($request->all());
        return view('checkout.cash_on_delivery');
    }

    public function cancel(Request $request)
    {
        #dd($request->all());
        $listing = Listing::find($request->input('listing'));
        if($listing) {
            return redirect(route("listing", ['listing' => $listing, 'slug' => $listing->slug]));
        }
        return redirect(route("browse"));
    }

    public function callback(Request $request)
    {

        $paypal = new PaypalClassic();
        $paypal->setParams([
            'TOKEN' => $request->input('token'),
        ]);
        $response = $paypal->send('GetExpressCheckoutDetails');
		
		if (config('private.paypal_mode') == 'sandbox' || true) {
		
			if($response['ACK'] == 'Success') {
		        $session = CheckoutSession::find($response['CUSTOM']);
				$listing = $session->listing;

				$widget = '\App\Widgets\Order\\'.studly_case($listing->pricing_model->widget).'Widget';
				$widget = new $widget();
				$pricing = $widget->calculate_price($listing, $session->request);
		
				$capture_params = [
					'PAYERID'           			 => $response['PAYERID'],
					'TOKEN'             			 => $response['TOKEN'],
					'AMT'               => $response['AMT'],
					'PAYMENTREQUEST_0_PAYMENTACTION' => 'Sale',
					'PAYMENTREQUEST_0_NAME' => $response['PAYMENTREQUEST_0_NAME'],
					'PAYMENTREQUEST_0_SELLERPAYPALACCOUNTID' => $response['PAYMENTREQUEST_0_SELLERPAYPALACCOUNTID'],
					'PAYMENTREQUEST_0_AMT' => (float) $response['PAYMENTREQUEST_0_AMT'],
					'PAYMENTREQUEST_0_PAYMENTREQUESTID' => 'SELLER',
					'PAYMENTREQUEST_0_CURRENCYCODE' => $response['PAYMENTREQUEST_0_CURRENCYCODE'],
					'PAYMENTREQUEST_0_DESC' => $response['PAYMENTREQUEST_0_DESC'],
					'PAYMENTREQUEST_0_CUSTOM' => (int) $response['PAYMENTREQUEST_0_CUSTOM'],
				];

				if($response['PAYMENTREQUEST_1_AMT']) {
					$capture_params += [
						'PAYMENTREQUEST_1_PAYMENTACTION' => 'Sale',
						'PAYMENTREQUEST_1_SELLERPAYPALACCOUNTID' => $response['PAYMENTREQUEST_1_SELLERPAYPALACCOUNTID'],
						'PAYMENTREQUEST_1_AMT' => (float) $response['PAYMENTREQUEST_1_AMT'],
						'PAYMENTREQUEST_1_PAYMENTREQUESTID' => 'MARKETPLACE',
						'PAYMENTREQUEST_1_CURRENCYCODE' => $response['PAYMENTREQUEST_1_CURRENCYCODE'],
						'PAYMENTREQUEST_1_DESC' => $response['PAYMENTREQUEST_1_DESC'],
						'PAYMENTREQUEST_1_CUSTOM' => (int)  $response['PAYMENTREQUEST_1_CUSTOM'],
					];
				}

				#dd($response);
				$paypal->setParams($capture_params);
				$checkout_response = $paypal->send('DoExpressCheckoutPayment');
				#dump($checkout_response);
				if($checkout_response['ACK'] == 'Success') {
				    $session = CheckoutSession::find($response['CUSTOM']);
					$listing = $session->listing;
					$gateway = $listing->user->payment_gateway($session->payment_provider->key);
					
					$authorization_id =  $request->input('token');
					$order_params = [
						'buyer_id' => auth()->user()->id,
						'seller_id' => $listing->user->id,
						'authorization_id' => $checkout_response['TOKEN'],
						'pricing' => $pricing,
						'request' => $session->request,
						'payment_provider' => $session->payment_provider,
						'payment_gateway_id' => $gateway->id,
						'shipping_address' => $session->extra_attributes['shipping_address'],
						'billing_address' => $session->extra_attributes['billing_address'],
						'listing' => $session->listing,
					];
					$order = $this->create_order($order_params);
					event(new OrderPlaced($order));
					alert()->success(__('Your order was placed successfully.'));
					
					//also mark it as accepted
					$order->accepted_at = Carbon::now();
					$order->status = 'accepted';
					$order->save();
					Mail::to($order->user->email)->send(new AcceptPurchase($order));
					
					return redirect(route('account.purchase-history.index'));
				}
				dd($checkout_response);
				
			}
		}
		
		/*
        $session = CheckoutSession::find($response['CUSTOM']);
        $listing = $session->listing;

        $widget = '\App\Widgets\Order\\'.studly_case($listing->pricing_model->widget).'Widget';
        $widget = new $widget();
        $pricing = $widget->calculate_price($listing, $session->request);

        if($response['ACK'] == 'Success') {
			$gateway = $listing->user->payment_gateway($session->payment_provider->key);
			
            $authorization_id =  $request->input('token');
            $order_params = [
                'buyer_id' => auth()->user()->id,
                'seller_id' => $listing->user->id,
                'authorization_id' => $authorization_id,
                'pricing' => $pricing,
                'request' => $session->request,
                'payment_provider' => $session->payment_provider,
                'payment_gateway_id' => $gateway->id,
                'shipping_address' => $session->extra_attributes['shipping_address'],
                'billing_address' => $session->extra_attributes['billing_address'],
                'listing' => $session->listing,
            ];
            $order = $this->create_order($order_params);
            event(new OrderPlaced($order));
            alert()->success(__('Your order was placed successfully.'));
            return redirect(route('account.purchase-history.index'));
        }

        alert()->danger(__('There was an error placing your order.'));
        return redirect(route('account.purchase-history.index'));*/
    }

}
