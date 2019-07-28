<?php

namespace App\Http\Controllers\Payments;

use App\Models\Order;
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

/*
 * Methods
 *  index -> shows the payment page or redirects
 *  store -> processes the payment and redirects to the purchase history
 *
 */

class BaseController extends Controller
{

    public function error_page(Request $request) {
dd(5);
    }

    protected function create_order($params) {
#dd($params);
        $buyer_id = $params['buyer_id'];
        $seller_id = $params['seller_id'];
        $listing = $params['listing'];
        $pricing = $params['pricing'];
        $listing_options = collect($params['request']);
        $payment_provider = $params['payment_provider'];
        $payment_gateway_id = $params['payment_gateway_id'];
        $authorization_id = $params['authorization_id'];

        $order = new Order();
        $order->user_id = $buyer_id;
        $order->service_fee = $pricing['service_fee'];
        $order->payment_gateway_id = $payment_gateway_id;
        $order->amount = $pricing['total'];
        $order->currency = $listing->currency;
        $order->authorization_id = $authorization_id;
        $order->capture_id = null;
        $order->processor = $payment_provider->key;

        $order->seller_id = $seller_id;
        $order->listing_id = $listing->id;
        #$order->token = $request->get('token');
        $order->listing_options = $listing_options->except([
            'card', 'token'
        ]);

        $order->user_choices = $pricing['user_choice'];
        $order->shipping_address = $params['shipping_address'];
        $order->billing_address = $params['billing_address'];
        $order->save();

        return $order;
    }

}
