<?php

namespace App\Http\Controllers\Account;

use App\Models\PaymentGateway;
use App\Models\PaymentProvider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\UpdateUserProfile;
use Image;
use Storage;
use GeoIP;
use Date;
use Log;

class BankAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        /*$countries = collect(json_decode(file_get_contents(resource_path("data/stripe-countries.json")), true));
        $country = $request->input('country', GeoIP::getCountry());
        $account = [];
        $user = auth()->user();
        $stripe_info = $user->payment_gateway('stripe');
        $individual = [];
        \Stripe\Stripe::setApiKey(config('marketplace.stripe_secret_key'));
        if($stripe_info) {
            $account = \Stripe\Account::retrieve($stripe_info->gateway_id);
            $country = $account->country;
            $currency = $countries->firstWhere('id', $country)['default_currency'];

            if($account->external_accounts->data)
                $external_account = $account->external_accounts->data[0];
            #dd($external_account.account_holder_name);

            $countries = $countries->reject(function ($option) use($account) {
                return $option['id'] != $account->country;
            });

            if(isset($account->legal_entity)) {
                $individual = $account->legal_entity;
            } elseif(isset($account->individual)) {
                $individual = $account->individual;
            }
        }

        $currency = $countries->firstWhere('id', $country)['default_currency'];

        $fields = [];
        $exclude_fields = [];
        if($country != 'US' && $country != 'CA' && $country != 'AU') {
            $exclude_fields = ['state'];
        }
        if($country != 'US') {
            $exclude_fields[] = 'ssn_last_4';
        }

        $extra_fields = [];
        if($country == 'HK') {
            $extra_fields[] = ['id' => 'personal_id_number', 'label' => 'Hong Kong Identity Card Number (HKID)'];
        }
        if($country == 'CA') {
            $extra_fields[] = ['id' => 'personal_id_number', 'label' => 'Social Insurance Number (SIN)'];
        }
        if($country == 'SG') {
            $extra_fields[] = ['id' => 'personal_id_number', 'label' => 'National Registration Identity Card '];
        }

        $fields[] = ['id' => 'account_number', 'label' => 'Account number'];
        if($country == 'AU') {
            $fields[] = ['id' => 'routing_number', 'label' => 'BSB number'];
        }
        if($country == 'BR') {
            $fields[] = ['id' => 'bank_code', 'label' => 'Bank code'];
            $fields[] = ['id' => 'branch_code', 'label' => 'Branch code'];
        }
        elseif($country == 'CA') {
            $fields[] = ['id' => 'institution_number', 'label' => 'Institution Number'];
            $fields[] = ['id' => 'transit_number', 'label' => 'Transit Number'];
        }
        elseif($country == 'US') {
            $fields[] = ['id' => 'routing_number', 'label' => 'Routing number'];
        }
        elseif($country == 'HK') {
            $fields[] = ['id' => 'clearing_code', 'label' => 'Clearing code'];
            $fields[] = ['id' => 'branch_code', 'label' => 'Branch code'];
        }
        elseif($country == 'JP') {
            $fields[] = ['id' => 'bank_name', 'label' => 'Bank Name'];
            $fields[] = ['id' => 'branch_name', 'label' => 'Branch Name'];
            $fields[] = ['id' => 'bank_code', 'label' => 'Bank code'];
            $fields[] = ['id' => 'branch_code', 'label' => 'Branch code'];
        }
        elseif($country == 'SG') {
            $fields[] = ['id' => 'bank_code', 'label' => 'bank_code'];
            $fields[] = ['id' => 'branch_code', 'label' => 'branch_code'];
        }
        elseif($country == 'NZ') {
            $fields[] = ['id' => 'routing_number', 'label' => 'Routing number'];
        }
        elseif($country == 'GB') {
            $fields[] = ['id' => 'routing_number', 'label' => 'Sort Code'];
        }
        else {
            $fields = [['id' => 'account_number', 'label' => 'IBAN']];
        }

        $days = array_combine(range(1,31), range(1,31));
        $months = [];
        Date::setLocale(config('app.locale'));
        for($m=1; $m<=12; ++$m){
            $months[$m] = ucfirst(Date::parse(mktime(0, 0, 0, $m, 1))->format('F'));
        }
        $years = range(date('Y')-100, date('Y'));
        $years = array_combine($years, $years);*/

		$payment_providers = PaymentProvider::with(['identifier' => function ($query) {
            $query->where('user_id', auth()->user()->id);
        }])->where('is_enabled', 1)->get();

        return view('account.bank_account', compact('payment_providers'));
        #return view('account.bank_account', compact('user', 'countries', 'fields', 'country', 'currency', 'days', 'months', 'years', 'exclude_fields', 'account', 'extra_fields', 'external_account', 'payment_providers', 'individual'));
    }
	
	//this is for a custom callback method
    public function unlink($provider, Request $request) {
        $provider = PaymentProvider::find($provider);
		$gateway = PaymentGateway::find($provider->identifier->id);
		#dd($gateway);
        $gateway->delete();

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
    }

    public function store(Request $request)
    {
dd('store');
        //create a custom account
        \Stripe\Stripe::setApiKey(config('marketplace.stripe_secret_key'));
        $user = auth()->user();
        $stripe_info = $user->payment_gateway('stripe');
        if(!$stripe_info) {
            try {
                $account = \Stripe\Account::create([
                    "type" => "custom",
                    "country" => $request->input('country'),
                    "email" => 'test-' . $request->input('country') . '-' . auth()->user()->email
                ]);
            } catch (\Exception $e) {
                return ['status' => false, 'error' => $e->getMessage()];
            }
        } else {
            $account = \Stripe\Account::retrieve($stripe_info->gateway_id);
        }

        $payment_gateway = PaymentGateway::firstOrCreate([
            'name' => 'stripe',
            'gateway_id' => $account->id,
            'user_id' => $user->id
        ]);
        $is_new = $payment_gateway->wasRecentlyCreated;

        if($request->input('city'))
            $account->legal_entity->address->city = $request->input('city');
        if($request->input('country'))
            $account->legal_entity->address->country = $request->input('country');
        if($request->input('address_line_1'))
            $account->legal_entity->address->line1 = $request->input('address_line_1');
        if($request->input('postal_code'))
            $account->legal_entity->address->postal_code = $request->input('postal_code');
        if($request->input('state'))
            $account->legal_entity->address->state = $request->input('state');

        if($request->input('dob_day'))
            $account->legal_entity->dob->day = $request->input('dob_day');
        if($request->input('dob_month'))
            $account->legal_entity->dob->month = $request->input('dob_month');
        if($request->input('dob_year'))
            $account->legal_entity->dob->year = $request->input('dob_year');

        if($request->input('first_name'))
            $account->legal_entity->first_name = $request->input('first_name');
        if($request->input('last_name'))
            $account->legal_entity->last_name = $request->input('last_name');

        $account->legal_entity->type = 'individual';

        if($request->input('external_account'))
            $account->external_account = $request->input('external_account');

        if(!$account->tos_acceptance->date) {
            $account->tos_acceptance->date = time();
            $account->tos_acceptance->ip = '80.189.218.119';
        }

        try {
            $account->save();
        } catch (\Exception $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
        $payment_gateway->gateway_id = $account->id;
        $payment_gateway->token = $request->input('external_account');
        $payment_gateway->metadata = $account;
        $payment_gateway->save();

        $user->can_accept_payments = true;
        $user->save();

        if($is_new) {
            alert()->success(__('Awesome! You can now accept payments and start selling.'));
        } else {
            alert()->success(__('Successfully updated!'));
        }

        return ['status' => true, 'account' => $account, 'redirect' => route('account.bank-account.index')];
    }

}
