<?php

namespace App\Http\Controllers\Payments;

use App\Models\CheckoutSession;
use App\Models\PaymentGateway;
use App\Models\PaymentProvider;
use App\Models\Order;
use Hashids;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\UpdateUserProfile;
use Image;
use Curl;
use Storage;
use GeoIP;
use Date;
use URL;
use App\Support\PaypalClassic;
use Socialite;
use App\Events\OrderPlaced;
use Carbon\Carbon;
/*
 * Methods
 *  index -> shows the payment page or redirects
 *  store -> processes the payment and redirects to the purchase history
 *
 */

class ExternalController extends BaseController
{
    public function accept($order) {

        $payment_provider = $order->payment_gateway->payment_provider;
		
		$params = [];
		$params['client_id'] = $provider->client_id;
		$params['client_secret'] = $provider->client_secret;
		$params['order_id'] = $order->id;
        $response = Curl::to($payment_provider->connection_url."/api/transaction/accept")
            ->withData( $params )
            ->asJson()
            ->get();
        if($response) {
            return $response->transaction;
        }
        return false;
    }
	
    public function decline($order) {
        $payment_provider = $order->payment_gateway->payment_provider;
		
		$params = [];
		$params['client_id'] = $provider->client_id;
		$params['client_secret'] = $provider->client_secret;
		$params['order_id'] = $order->id;
        $response = Curl::to($payment_provider->connection_url."/api/transaction/deny")
            ->withData( $params )
            ->asJson()
            ->get();
        if($response) {
            return $response->transaction;
        }
        return true;
    }

    public function index($session, $key, Request $request)
    {
        $listing = $session->listing;
        #calculate the real price of the order
        $widget = '\App\Widgets\Order\\'.studly_case($listing->pricing_model->widget).'Widget';
        $widget = new $widget();
        $pricing = $widget->calculate_price($listing, $session->request);
		
        $payment_provider = PaymentProvider::where('key', $key)->first();
        $user = auth()->user();

        $params = [];
        $params['session'] = $session;
        $params['key'] = $key;
        $return_url = route('payments.external.callback', $params);

        $params = [
            "email" => $user->email,
            "title" => $listing->title,
            "description" => $listing->title,

            "amount" => $pricing['total'],
            "currency" => $listing->currency,
            "fee" => $pricing['service_fee'],

            "seller_email" => $listing->user->email,
            "billing_address" => $user->billing_address,
            "shipping_address" => $user->shipping_address,
			
            "session" => $session->id,
            "key" => $key,

            "return_url" => $return_url,
        ];

        #dd($payment_provider->connection_url."/api/checkout");
        $response = Curl::to($payment_provider->connection_url."/api/checkout")
            ->withData( $params )
            ->asJson()
            ->get();
		#dd($response);
        if($response) {
            return redirect($response->redirect_url);
        } else {
            alert()->danger(__('Oops, something went wrong. Please try again later.'));
            return back();
        }
    }

    public function connect($key, Request $request)
    {
        $payment_provider = PaymentProvider::where('key', $key)->first();
        return redirect($payment_provider->connection_url.'/connect/'. auth()->user()->id .'?callback='. route('payments.external.connected', $key));
    }

    public function connected($provider, Request $request) {
        $identifier = $request->input('identifier');
        $provider = PaymentProvider::where('key', $provider)->first();

		$params = [];
		$params['client_id'] = $provider->client_id;
		$params['client_secret'] = $provider->client_secret;
		$params['identifier'] = $identifier;
		$response = Curl::to($provider->connection_url."/api/connect/verify")
            ->withData( $params )
            ->asJson()
            ->get();
		if($response->status == true) {
			$user = auth()->user();
			$payment_gateway = PaymentGateway::firstOrCreate([
				'name' => $provider->key,
				'gateway_id' => $identifier,
				'user_id' => $user->id
			]);

			alert()->success(__('Awesome! You can now accept payments and start selling.'));
			return redirect()->route('account.bank-account.index');
		} else {
			alert()->danger(__('Error! Unable to connect account.'));
		}
		
		return redirect()->route('account.bank-account.index');

    }

    public function cancel(Request $request)
    {
        return redirect(route("browse"));
    }

    public function callback($checkout_session, Request $request)
    {
#dd($checkout_session);
        #$checkout_session = CheckoutSession::find($checkout_session);
        #dd($checkout_session->payment_provider);
        $payment_provider = PaymentProvider::where('key', $checkout_session->payment_provider_key)->first();
        #dd($payment_provider);

        #dd($payment_provider->connection_url);
        $status = $request->input('status');
        $hash = $request->input('hash');

        if($status == 'OK') {
            //validate the hash
            $params = [];
            $params['hash'] = $request->input('hash');
            $params['session'] = $checkout_session->id;
            $params['client_id'] = $payment_provider->client_id;
            $params['client_secret'] = $payment_provider->client_secret;
            $transaction_id = $request->input('txid');

            $response = Curl::to(rtrim($payment_provider->connection_url, '/')."/api/transaction/verify")
                ->withData( $params )
                ->asJson()
				->returnResponseObject()
                ->get();

			#dd($response, $params);

            $pricing = $checkout_session->request;
            $listing = $checkout_session->listing;

            $widget = '\App\Widgets\Order\\'.studly_case($listing->pricing_model->widget).'Widget';
            $widget = new $widget();
            $pricing = $widget->calculate_price($listing, $checkout_session->request);
            #dd(auth()->user()->id);

            if($response) {
                $order_params = [
                    'buyer_id' => auth()->user()->id,
                    'seller_id' => $listing->user->id,
                    'authorization_id' => $transaction_id,
                    'pricing' => $pricing,
                    'request' => $checkout_session->request,
                    'payment_provider' => $checkout_session->payment_provider,
                    'payment_gateway_id' => $checkout_session->payment_provider->identifier->id,
                    'shipping_address' => $checkout_session->extra_attributes->shipping_address,
                    'billing_address' => $checkout_session->extra_attributes->billing_address,
                    'listing' => $checkout_session->listing,
                ];

                $order = $this->create_order($order_params);
				if($request->input('accept') == 'OK') {
					$order->accepted_at = Carbon::now();
					$order->status = 'accepted';
					$order->save();
				}
				
                alert()->success(__('Your order was placed successfully.'));
            } else {
                alert()->danger(__('Oops, something went wrong. Please try again later.'));
            }

        } else {
            alert()->danger(__('Oops, something went wrong. Please try again later.'));
        }

        return redirect()->route('account.purchase-history.index');
    }



}
