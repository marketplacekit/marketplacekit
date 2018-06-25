<?php

namespace App\Http\Controllers;

use App\Events\OrderPlaced;
use App\Support\PaypalClassic;
use Illuminate\Http\Request;
use App\Models\Listing;
use App\Models\Order;
use Carbon\Carbon;
use URL;

class PaypalController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function start($listing, Request $request) {

        $widget = '\App\Widgets\Order\\'.studly_case($listing->pricing_model->widget).'Widget';
        $widget = new $widget();
        $result = $widget->calculate_price($listing, request()->all());

        $query_params = collect($request->query())->reject(function ($k, $v) {
            return substr( $v, 0, 3 ) === "ic-" || substr( $v, 0, 1 ) === "_";
        });
        $query_params['listing_id'] = $listing->id;
        $query_string = '';
        if($query_params)
            $query_string = http_build_query($query_params->toArray());
        #dd($query_string);
        $paypal = new PaypalClassic();
        $paypal->setParams([
            'cancelUrl' => URL::route('paypal.cancel', ['listing' => $listing->id],true),
            'returnUrl' => URL::route('paypal.callback', [],true),
            'AMT'       => $result['total'],
            'PAYMENTREQUEST_0_PAYMENTACTION' => 'Sale',
            'PAYMENTREQUEST_0_SELLERPAYPALACCOUNTID' => $listing->user->paypal_email,
            'PAYMENTREQUEST_0_AMT' => (float) number_format($result['total']-$result['service_fee'], 2, '.', ''),
            'PAYMENTREQUEST_0_PAYMENTREQUESTID' => 'SELLER',
            'PAYMENTREQUEST_0_CURRENCYCODE' => $listing->currency,
            'PAYMENTREQUEST_0_DESC' => "Seller fee",
            'PAYMENTREQUEST_0_CUSTOM' => $query_string,
            'PAYMENTREQUEST_1_PAYMENTACTION' => 'Sale',
            'PAYMENTREQUEST_1_SELLERPAYPALACCOUNTID' => setting('paypal_email'),
            'PAYMENTREQUEST_1_AMT' => $result['service_fee'],
            'PAYMENTREQUEST_1_PAYMENTREQUESTID' => 'MARKETPLACE',
            'PAYMENTREQUEST_1_CURRENCYCODE' => $listing->currency,
            'PAYMENTREQUEST_1_DESC' => "Marketplace fee",
            'PAYMENTREQUEST_1_CUSTOM' => $query_string,
        ]);
        $response = $paypal->send('SetExpressCheckout');

        if(isset($response['TOKEN'])) {
            $url = "https://www.paypal.com/checkoutnow?token=" . $response['TOKEN'];
            if (setting('paypal_mode') == 'sandbox')
                $url = "https://www.sandbox.paypal.com/checkoutnow?token=" . $response['TOKEN'];
            return redirect($url);
        } else {
            dd($response);
        }

    }


    public function cancel(Request $request) {
        $listing = Listing::find($request->input('listing'));
        if($listing) {
            return redirect(route("listing", ['listing' => $listing, 'slug' => $listing->slug]));
        }
        return redirect(route("browse"));
    }

    public function callback(Request $request) {

        $paypal = new PaypalClassic();
        $paypal->setParams([
            'TOKEN' => $request->input('token'),
        ]);
        $checkout_details = $paypal->send('GetExpressCheckoutDetails');
        parse_str($checkout_details['PAYMENTREQUEST_0_CUSTOM'], $query_params);

        $listing = Listing::find($query_params['listing_id']);
        $widget = '\App\Widgets\Order\\'.studly_case($listing->pricing_model->widget).'Widget';
        $widget = new $widget();
        $result = $widget->calculate_price($listing, $query_params);

        $paypal = new PaypalClassic();
        $paypal->setParams([
            'cancelUrl' => URL::route('paypal.cancel', ['listing' => $listing->id],true),
            'returnUrl' => URL::route('paypal.callback', [],true),
            'AMT' => $result['total'],
            'PAYERID' => $checkout_details['PAYERID'],
            'TOKEN' => $request->input('token'),
            'PAYMENTREQUEST_0_PAYMENTACTION' => 'Sale',
            'PAYMENTREQUEST_0_SELLERPAYPALACCOUNTID' => $listing->user->paypal_email,
            'PAYMENTREQUEST_0_AMT' => (float) number_format($result['total']-$result['service_fee'], 2, '.', ''),
            'PAYMENTREQUEST_0_PAYMENTREQUESTID' => 'SELLER',
            'PAYMENTREQUEST_0_CURRENCYCODE' => $listing->currency,
            'PAYMENTREQUEST_0_DESC' => "Seller fee",
            'PAYMENTREQUEST_0_CUSTOM' => $checkout_details['PAYMENTREQUEST_0_CUSTOM'],
            'PAYMENTREQUEST_1_PAYMENTACTION' => 'Sale',
            'PAYMENTREQUEST_1_SELLERPAYPALACCOUNTID' => setting('paypal_email'),
            'PAYMENTREQUEST_1_AMT' => $result['service_fee'],
            'PAYMENTREQUEST_1_PAYMENTREQUESTID' => 'MARKETPLACE',
            'PAYMENTREQUEST_1_CURRENCYCODE' => $listing->currency,
            'PAYMENTREQUEST_1_DESC' => "Marketplace fee",
            'PAYMENTREQUEST_1_CUSTOM' => $checkout_details['PAYMENTREQUEST_1_CUSTOM'],
        ]);
        $response = $paypal->send('DoExpressCheckoutPayment');
        #dd($response);
        if($response['ACK'] == 'Success') {

            $order = new Order();
            if (auth()->check()) {
                $order->user_id = auth()->user()->id;
            }
            $order->service_fee = $result['service_fee'];
            $order->payment_gateway_id = 0;
            $order->amount = $result['total'];
            $order->currency = $listing->currency;
            $order->authorization_id = $response['PAYMENTINFO_0_TRANSACTIONID'];
            $order->capture_id = null;
            $order->processor = 'paypal_classic';

            $order->seller_id = $listing->user->id;
            $order->listing_id = $listing->id;
            $order->token = $request->input('token');
            $order->listing_options = $query_params;
            $order->customer_details = [
                "fullname" => @$checkout_details['SHIPTOSTREET'],
                "address_line1" => @$checkout_details['SHIPTOSTREET'],
                "address_city" => @$checkout_details['SHIPTOCITY'],
                "address_state" => @$checkout_details['SHIPTOSTATE'],
                "address_zip" => @$checkout_details['SHIPTOZIP'],
                "address_country" => @$checkout_details['SHIPTOCOUNTRYNAME']
            ];

            $order->accepted_at = Carbon::now();
            $order->status = 'accepted';
            $order->save();

            event(new OrderPlaced($order));
            alert()->success(__('Your order was placed successfully.'));
            return redirect(route('account.purchase-history.index'));
        }

        alert()->danger(__('There was an error placing your order.'));
        return redirect(route('account.purchase-history.index'));
    }

}
