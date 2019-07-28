<?php

namespace App\Http\Controllers\Payments;

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
use App\Events\OrderPlaced;

/*
 * Methods
 *  index -> shows the payment page or redirects
 *  store -> processes the payment and redirects to the purchase history
 *
 */

class OfflineController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($session, Request $request)
    {
        $listing = $session->listing;
        #calculate the real price of the order
        $widget = '\App\Widgets\Order\\'.studly_case($listing->pricing_model->widget).'Widget';
        $widget = new $widget();
        $pricing = $widget->validate_payment($listing, $session->request);

        #if no identifier create one
        if(!$session->payment_provider->identifier) {
            dd("Error");
        }
        #dd($session->payment_provider->identifier);

        #now we simply place the order
        $transaction_id = Hashids::encode($session->id);
        $order_params = [
            'buyer_id' => auth()->user()->id,
            'seller_id' => $listing->user->id,
            'authorization_id' => $transaction_id,
            'pricing' => $pricing,
            'request' => $session->request,
            'payment_provider' => $session->payment_provider,
            'payment_gateway_id' => $session->payment_provider->identifier->id,
            'shipping_address' => $session->extra_attributes['shipping_address'],
            'billing_address' => $session->extra_attributes['billing_address'],
            'listing' => $session->listing,
        ];
        #dd($order_params);
        $order = $this->create_order($order_params);

        //now decrease listing quantity
        event(new OrderPlaced($order));
        alert()->success(__('Your reservation was placed successfully. Your order number is '.$order->hash));

        return redirect()->route('account.purchase-history.index');
    }

    public function connect($key, Request $request)
    {
        $provider = PaymentProvider::where('key', $key)->where('is_offline', 1)->first();
        $user = auth()->user();

        #$identifier = $request->input('identifier');
        #$provider = PaymentProvider::find($provider);
        $identifier = $provider->key .'_'.$user->id; //any random

        $user = auth()->user();
        $payment_gateway = PaymentGateway::firstOrCreate([
            'name' => $provider->key,
            'gateway_id' => $identifier,
            'user_id' => $user->id
        ]);
		
		
        $user->can_accept_payments = true;
        $user->save();

        alert()->success(__('You can now accept payments and start selling.'));

        return redirect()->route('account.bank-account.index');
    }

    public function store(Request $request)
    {
        dd($request->all());
        return view('checkout.cash_on_delivery');
    }

    public function accept($order) {

        return uniqid();
    }

    public function decline($order) {
        return uniqid();
    }

    //this is for a custom callback method
   /* public function unlink($provider, Request $request) {
        $provider = PaymentProvider::find($provider);
        $provider->identifier->delete();

        alert()->success(__('Unlinked account'));
        return redirect()->route('account.bank-account.index');
    }

    public function callback($provider, Request $request) {

        $identifier = $request->input('identifier');
        $provider = PaymentProvider::find($provider);

        $user = auth()->user();
        $payment_gateway = PaymentGateway::firstOrCreate([
            'name' => $provider->key,
            'gateway_id' => $identifier,
            'user_id' => $user->id
        ]);

        alert()->success(__('Awesome! You can now accept payments and start selling.'));

        return redirect()->route('account.bank-account.index');
    }*/

}
