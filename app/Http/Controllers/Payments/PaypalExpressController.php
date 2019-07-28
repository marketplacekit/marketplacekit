<?php

namespace App\Http\Controllers\Payments;

use App\Models\CheckoutSession;
use App\Models\PaymentGateway;
use App\Models\PaymentProvider;
use Hashids;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\UpdateUserProfile;
use Image;
use Storage;
use GeoIP;
use Date;
use URL;
use App\Support\PaypalClassic;
use Socialite;
use App\Events\OrderPlaced;

/*
 * Methods
 *  index -> shows the payment page or redirects
 *  store -> processes the payment and redirects to the purchase history
 *
 */

class PaypalExpressController extends BaseController
{
    public function accept($order) {
	

        $paypal = new PaypalClassic();
        $paypal_params = [
            'AUTHORIZATIONID'   => $order->authorization_id,
            'AMT'     			=> $order->amount,
            'CURRENCYCODE'     	=> $order->currency,
            'COMPLETETYPE'     	=> 'Complete',
        ];
        $paypal->setParams($paypal_params);
        $response = $paypal->send('DoCapture');
		dd($response);
		
		if($response['ACK'] == 'Success') {
			$paypal = new PaypalClassic();
			$paypal_params = [
				'REFERENCEID'   	=> $order->authorization_id,
				'AMT'     			=> $order->service_fee,
				'CURRENCYCODE'     	=> $order->currency,
				'PAYMENTACTION'     => 'SALE',
			];
			$paypal->setParams($paypal_params);
			$response = $paypal->send('DoReferenceTransaction');
			dd($response);
			$capture_id = $response['AUTHORIZATIONID'].'|'.$response['TRANSACTIONID'];
			return $capture_id;
		} else {
            alert()->danger($response['L_LONGMESSAGE0']);
			return false;
		}
		
        return false;
    }
    public function decline($order) {
        return true;
    }

    public function index($session, Request $request)
    {
        $listing = $session->listing;
		$gateway = $listing->user->payment_gateway($session->payment_provider->key);
		#dd($gateway);
        if(!$gateway) {
            dd("NO SELLER ID");
        }

		#dd($session->payment_provider->extra_attributes['paypal_email']);
        #calculate the real price of the order
        $widget = '\App\Widgets\Order\\'.studly_case($listing->pricing_model->widget).'Widget';
        $widget = new $widget();
        $result = $widget->calculate_price($listing, $session->request);
		#dd($result);

        $query_params = collect($session->request)->reject(function ($k, $v) {
            return substr( $v, 0, 3 ) === "ic-" || substr( $v, 0, 1 ) === "_";
        });
        $query_params['listing_id'] = $listing->id;
        $query_string = '';
        if($query_params)
            $query_string = http_build_query($query_params->toArray());

        $paypal_email = $gateway->gateway_id;
		#dd($paypal_email);
        $paypal = new PaypalClassic();
        $paypal_params = [
            'cancelUrl' => route('payments.paypal-express.cancel', ['listing' => $listing->id],true),
            'returnUrl' => route('payments.paypal-express.callback', [], true),
            'AMT'       => $result['total'],
            'PAYMENTREQUEST_0_PAYMENTACTION' => 'Authorization',
            'PAYMENTREQUEST_0_SELLERPAYPALACCOUNTID' => $paypal_email,
            'PAYMENTREQUEST_0_AMT' => (float) number_format($result['total'], 2, '.', ''),
            'PAYMENTREQUEST_0_PAYMENTREQUESTID' => 'SELLER',
            'PAYMENTREQUEST_0_CURRENCYCODE' => $listing->currency,
            'PAYMENTREQUEST_0_DESC' => "Seller fee",
            'PAYMENTREQUEST_0_CUSTOM' => $session->id,
        ];

		if($result['price_items']) {
			foreach($result['price_items'] as $i => $price_item) {
				if($price_item['key'] == 'service') {
					#continue;
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
		}

        /*if($result['service_fee']) {
            $paypal_params += [
                'PAYMENTREQUEST_1_PAYMENTACTION' => 'Sale',
                'PAYMENTREQUEST_1_SELLERPAYPALACCOUNTID' => $session->payment_provider->extra_attributes['paypal_email'],
                'PAYMENTREQUEST_1_AMT' => $result['service_fee'],
                'PAYMENTREQUEST_1_PAYMENTREQUESTID' => 'MARKETPLACE',
                'PAYMENTREQUEST_1_CURRENCYCODE' => $listing->currency,
                'PAYMENTREQUEST_1_DESC' => "Marketplace fee",
                'PAYMENTREQUEST_1_CUSTOM' => $session->id,
            ];
        }*/
		
		$paypal_params['L_BILLINGTYPE0'] = 'MerchantInitiatedBilling';
		$paypal_params['L_BILLINGAGREEMENTDESCRIPTION0'] = 'SERVICE FEE '.$result['service_fee'].$listing->currency;
        dd($paypal_params);
		
        $paypal->setParams($paypal_params);
        $response = $paypal->send('SetExpressCheckout');
		#dd($response);
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
		/*$paypal = new PaypalClassic();
        $paypal->setParams([
		    'cancelUrl' => route('payments.paypal-express.connected', [],true),
            'returnUrl' => route('payments.paypal-express.connected', [], true),
            'AMT' => '0',
            'L_BILLINGTYPE0' => 'MerchantInitiatedBillingSingleAgreement',
            'L_BILLINGAGREEMENTDESCRIPTION0' => 'MerchantInitiatedBillingSingleAgreement',
            'PAYMENTREQUEST_0_PAYMENTACTION' => 'SALE',
            'PAYMENTREQUEST_0_AMT' => '0',
            'PAYMENTREQUEST_0_CURRENCYCODE' => 'GBP',
        ]);
        $response = $paypal->send('SetExpressCheckout');
		#dd($response);
        if(isset($response['TOKEN'])) {
		    $url = "https://www.paypal.com/webscr?cmd=_express-checkout&token=" . $response['TOKEN'];
            if (config('private.paypal_mode') == 'sandbox')
                $url = "https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&token=" . $response['TOKEN'];
            return redirect($url);
        } else {
            dd($response);
        }
		
		dd( $response );
		
					$paypal = new PaypalClassic();
			$paypal->setParams([
				'TOKEN' => $request->input('token'),
			]);
			$ba_response = $paypal->send('CreateBillingAgreement');
		*/
		$url = "https://app.marketplacekit.com/paypal/start?tenant=".current_tenant().'&mode='.config('private.paypal_mode').'&express=1';
		return redirect($url);
    }

    public function connected(Request $request) {
		#dd($request->all());
        $driver = "paypal";
        if (config('private.paypal_mode') == 'sandbox')
            $driver = "paypal_sandbox";
#dd($driver);
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

		#dd(config('services.paypal'));
		#config(['services.paypal_sandbox.client_id' => 'ATMLqeTq6x9BiZDEHR2F2CWTto_X4uuxfLSx4XGWO5SptbUer5fTeh78eiu5wMzGYlWd4igoxjlJiVad']);
        #config(['services.paypal_sandbox.client_secret' => 'EBzWPEO5GZbgO6Y9ZmzQYULh8IJuHiq1huqie3ArXteRSmptS6pzvC29KQycrQ7XpCXPcwb6o_lnm1Au']);
        #config(['services.paypal_sandbox.redirect' => 'https://app.marketplacekit.com/paypal/callback']);
				
        $paypal_user = Socialite::driver($driver)->stateless()->user();
		#dd($paypal_user);
        $payment_gateway = PaymentGateway::firstOrCreate([
            'name' => 'paypal_express',
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
        dd($request->all());
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
        $session = CheckoutSession::find($response['CUSTOM']);
        $listing = $session->listing;

        $widget = '\App\Widgets\Order\\'.studly_case($listing->pricing_model->widget).'Widget';
        $widget = new $widget();
        $pricing = $widget->calculate_price($listing, $session->request);
		#dd($response);
        if($response['ACK'] == 'Success') {
		
			$paypal = new PaypalClassic();
			$paypal->setParams([
				'TOKEN' => $request->input('token'),
			]);
			#$ba_response = $paypal->send('CreateBillingAgreement');
			#dump($ba_response);
		
			$gateway = $listing->user->payment_gateway($session->payment_provider->key);
            $authorization_id = $response['PAYERID'];
			
			$auth_params = [
				'TOKEN'							=>	$response['TOKEN'],
				'PAYERID'							=>	$response['PAYERID'],
				'PAYMENTREQUEST_0_PAYMENTACTION'	=>	'Authorization',
				'PAYMENTREQUEST_0_AMT'				=>	$response['PAYMENTREQUEST_0_AMT'],
				'PAYMENTREQUEST_0_CURRENCYCODE'		=>	$response['PAYMENTREQUEST_0_CURRENCYCODE'],
			];
			$paypal->setParams($auth_params);
			$auth_response = $paypal->send('DoExpressCheckoutPayment');
			#dd($auth_response);
			if($auth_response['ACK'] == 'Success') {
				$authorization_id =  $auth_response['PAYMENTINFO_0_TRANSACTIONID'];
			
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
				$order->billing_agreement_id = $ba_response['BILLINGAGREEMENTID'];
				$order->save();
				
				event(new OrderPlaced($order));
				alert()->success(__('Your order was placed successfully.'));
				return redirect(route('account.purchase-history.index'));
			}
        }

        alert()->danger(__('There was an error placing your order.'));
        return redirect(route('account.purchase-history.index'));
    }

}
