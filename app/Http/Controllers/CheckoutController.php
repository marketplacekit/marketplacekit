<?php

namespace App\Http\Controllers;

use App\Events\OrderPlaced;
use Illuminate\Http\Request;
use App\Models\Listing;
use App\Models\Order;
use Carbon\Carbon;

class CheckoutController extends Controller
{

    protected $category_id;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function process($listing, Request $request) {
        /*$order = Order::where('id', 66)->first();
        event(new OrderPlaced($order));
        die();*/
        try {
            \Stripe\Stripe::setApiKey(config('marketplace.stripe_secret_key'));

            #calculate the real price of the order
            $widget = '\App\Widgets\Order\\'.studly_case($listing->pricing_model->widget).'Widget';
            $widget = new $widget();
            $validation_result = $widget->validate_payment($listing, $request);
            #dd($validation_result);

            $payment_gateway = $listing->user->payment_gateway('stripe');

            if(!$payment_gateway) {
                $error = __("This user cannot accept payments currently. No funds will be taken. Please contact the seller directly.");
                return response()->json( ['status' => false, 'error' => $error], 500 )->setStatusCode(500, $error);
            }

            #create customer to charge
            $customer = \Stripe\Customer::create(array(
                'email' => $request->input('email'),
                'source'  => $request->input('token.id'),
            ));

            #create a token
            $token = \Stripe\Token::create(array(
                "customer" => $customer->id,
            ), ["stripe_account" => $payment_gateway->gateway_id]);

            $quantity = $request->input('quantity', 1);
            #charge the customer and send funds to seller account
            $charge = \Stripe\Charge::create(array(
                'amount'  	 		=> $validation_result['total']*100,
                'currency' 			=> $listing->currency,
                "description" 		=> $listing->title . " x".$quantity,
                "capture" 			=> false,
                "application_fee" 	=> $validation_result['service_fee']*100,
                "source" 				=> $token->id,
            ), ["stripe_account" 	=> $payment_gateway->gateway_id]);

            #print_r($charge);
            $order = new Order();
            if(auth()->check()) {
                $order->user_id = auth()->user()->id;
            }
            $order->service_fee = $validation_result['service_fee'];
            $order->payment_gateway_id = $payment_gateway->id;
            $order->amount = $validation_result['total'];
            $order->currency = $listing->currency;
            $order->authorization_id = $charge->id;
            $order->capture_id = null;
            $order->processor = 'stripe';

            $order->seller_id = $listing->user->id;
            $order->listing_id = $listing->id;
            $order->token = $request->get('token');
            $order->listing_options = $request->except([
                'card', 'token'
            ]);

            $order->user_choices = $validation_result['user_choice'];
            $order->customer_details = $request->only([
                'card.name', 'card.address_line1', 'card.address_line2', 'card.address_city',
                'card.address_state', 'card.address_zip', 'card.address_country', 'card.email', 'card.phone'
            ]);
            $order->save();

            $charge->metadata = ['order_id' => $order->id];
            $charge->save();


            //now decrease listing quantity
            event(new OrderPlaced($order));
            alert()->success(__('Your order was placed successfully. Please note that funds will only be taken once the seller confirms the order.'));
            return ['status' => true, 'transaction_id' => $order->id, 'url' => route('account.purchase-history.index')];
        } catch (\Stripe\Error\Base $e) {
            return response()->json( ['status' => false, 'error' =>$e->getMessage()], 500 )->setStatusCode(500, $e->getMessage());
        }

        #never executes
        return response()->json( ['status' => false, 'error' => "Something went wrong"], 500 )->setStatusCode(500, "Something went wrong");
    }

    public function getContact($id, Request $request) {
        $listing = Listing::find($id);

        $data = [];
        $data['listing'] = $listing;
        return view('order.contact', $data);
    }

    public function postContact($id, Request $request) {

        //send an email to the seller and add to his inbox
        $listing = Listing::findOrFail($id);
        $mail = Mail::to($listing->user)->send(new ListingContact($listing, $request->all()));
        return redirect('order/'.$id.'/contact');

    }

}
