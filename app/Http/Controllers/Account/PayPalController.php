<?php

namespace App\Http\Controllers\Account;

use App\Models\PaymentGateway;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\UpdateUserProfile;
use Image;
use Storage;
use GeoIP;
use Date;
use Socialite;

class PayPalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function connect(Request $request)
    {
        //
        $driver = "paypal";
        if(setting('paypal_mode') == 'sandbox')
            $driver = "paypal_sandbox";

        return Socialite::driver($driver)->stateless()->redirect();
    }

    public function callback(Request $request)
    {
        //
        $driver = "paypal";
        if(setting('paypal_mode') == 'sandbox')
            $driver = "paypal_sandbox";
        $user = Socialite::driver($driver)->stateless()->user();

        $local_user = auth()->user();
        $local_user->paypal_email = $user->email;
        $local_user->can_accept_payments = true;
        $local_user->save();

        #add payment gateway
        $payment_gateway = PaymentGateway::firstOrCreate([
            'name' => 'paypal_classic',
            'gateway_id' => $user->email,
            'user_id' => $local_user->id
        ]);
        $is_new = $payment_gateway->wasRecentlyCreated;

        $payment_gateway->gateway_id = $user->email;
        $payment_gateway->token = $request->input('external_account');
        $payment_gateway->metadata = $user;
        $payment_gateway->save();

        if($is_new) {
            alert()->success(__('Awesome! You can now accept payments and start selling.'));
        } else {
            alert()->success(__('Successfully updated!'));
        }

        return redirect(route('account.bank-account.index'));
    }



}
