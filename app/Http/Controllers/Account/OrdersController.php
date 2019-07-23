<?php

namespace App\Http\Controllers\Account;

use App\Mail\AcceptPurchase;
use App\Mail\DeclinePurchase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Mail;
use Talk;

class OrdersController extends Controller
{

    public function index()
    {


        $orders = Order::with('listing', 'user')->where('seller_id', auth()->user()->id)->orderBy('id', 'DESC')->paginate(10);
        return view('account.orders.index', compact('orders'));
    }

    public function show($order)
    {

        $this->authorize('update', $order);

        /*$user = auth()->user();
        dd($user->stripe_id);
        $user->stripe_id = 'hello world';
        $user->save();*/

        return view('account.orders.show', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $order)
    {
        //
        $this->authorize('update', $order);

        $user = auth()->user();

        if($request->input('status') == 'accept') {

            if($order->payment_gateway->name == 'stripe') {
                \Stripe\Stripe::setApiKey(config('marketplace.stripe_secret_key'));
                $charge = \Stripe\Charge::retrieve($order->authorization_id, ["stripe_account" => $order->payment_gateway->gateway_id]);
                $result = $charge->capture();
                $order->capture_id = $charge->id;
            }

            $order->accepted_at = Carbon::now();
            $order->status = 'accepted';
            $order->save();

            Mail::to($order->user->email)->send(new AcceptPurchase($order));

        }
        if($request->input('status') == 'decline') {

            if($order->payment_gateway->name == 'stripe') {
                \Stripe\Stripe::setApiKey(config('marketplace.stripe_secret_key'));
                $charge = \Stripe\Charge::retrieve($order->authorization_id, ["stripe_account" => $order->payment_gateway->gateway_id]);

                $refund = \Stripe\Refund::create(array(
                    "charge" => $charge->id
                ), ["stripe_account" 	=> $order->payment_gateway->gateway_id]);

                $order->refund_id = $refund->id;
            }

            $order->declined_at = Carbon::now();
            $order->status = 'declined';
            $order->save();

            Mail::to($order->user->email)->send(new DeclinePurchase($order));
        }

        if($request->input('notes')) {
            Talk::setAuthUserId(auth()->user()->id);
            Talk::sendMessageByUserId($order->user_id, $request->get('notes'));
            $order->user->increment('unread_messages');
        }

        $request->session()->flash('refresh_cache', 'OK');

        return redirect(route('account.orders.show', $order));
    }
}
