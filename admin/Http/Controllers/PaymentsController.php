<?php

namespace App\Http\Controllers\Admin;

use App\Models\PaymentProvider;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Listing;
use App\Models\Category;
use Kris\LaravelFormBuilder\FormBuilder;
use Modules\Panel\Forms\PaymentForm;
use Crypt;
use Setting;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
	#dd(config('marketplace.stripe_secret_key'));
        /*$default_providers = collect(json_decode(file_get_contents(resource_path('data/payment-providers.json'))));
		
        foreach($default_providers as $default_provider) {
            #dd(collect($default_provider)->toArray());
            $payment_provider = PaymentProvider::where('key', $default_provider->key)->first();
            if(!$payment_provider) {
                PaymentProvider::create(collect($default_provider)->toArray());
            }
        }*/

        $payment_providers = PaymentProvider::get();
		$enabled_provider_count = $payment_providers->filter(function ($value, $key) {
			return $value->is_enabled;
		})->count();
		
		Setting::set('payments_enabled', ($enabled_provider_count > 0));
		Setting::save();
		
        $data['payment_providers'] = $payment_providers;
        return view('panel::payment_providers.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(FormBuilder $formBuilder)
    {
        $form = $formBuilder->create('Modules\Panel\Forms\PaymentForm', [
            'method' => 'POST',
            'url' => route('panel.payments.store')
        ]);
        #dd($form);

        return view('panel::payment_providers.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        $payment_provider = new PaymentProvider();
        $payment_provider->name = $request->input('name');
        $payment_provider->key = $request->input('key');
        $payment_provider->display_name = $request->input('display_name');
        $payment_provider->description = $request->input('description');
        $payment_provider->payment_instructions = $request->input('payment_instructions');
        $payment_provider->is_enabled = $request->has('is_enabled');
        $payment_provider->position = $request->input('position');
        $payment_provider->icon = $request->input('icon');
        $payment_provider->is_offline = $request->input('key') == 'offline';
        $payment_provider->save();

        alert()->success('Successfully saved');
        return redirect()->route('panel.payments.index');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $data = [];
        $data['payment_provider'] = PaymentProvider::find($id);
        return view('panel::payment_providers.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($payment_provider, FormBuilder $formBuilder)
    {
		$payment_provider = PaymentProvider::find($payment_provider);
		
		$data = [];
		$data['payment_provider'] = $payment_provider;
		$data['form'] = $formBuilder->create(\Modules\Panel\Forms\PaymentForm::class, [
			'method' => 'PUT',
			'url' => route('panel.payments.update', $payment_provider),
			'model' => $payment_provider
		]);			
		return view('panel::payment_providers.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update($id, Request $request)
    {
        $payment_provider = PaymentProvider::find($id);

		if($payment_provider->required_keys) {
			foreach($payment_provider->required_keys as $required_key) {
				$value = $request->input($required_key);
				if($payment_provider->secret_keys && in_array($required_key, $payment_provider->secret_keys)) {
					$value = \Crypt::encryptString($value);
				}
				$payment_provider->extra_attributes[$required_key] = $value;
			}
        }
		$payment_provider->name = $request->input('name');
		$payment_provider->display_name = $request->input('display_name');
        $payment_provider->icon = $request->input('icon');
        $payment_provider->description = $request->input('description');
        $payment_provider->payment_instructions = $request->input('payment_instructions');
        $payment_provider->is_enabled = $request->input('is_enabled');
        $payment_provider->save();
		
		if($payment_provider->key == 'stripe') {
			if($request->input('secret_key'))
				Setting::set('stripe_secret_key', Crypt::encryptString($request->input('secret_key')));				
			
			if($request->input('publishable_key'))
				Setting::set('stripe_publishable_key', $request->input('publishable_key'));
			Setting::save();
		}		
		if($payment_provider->key == 'paypal_split') {
			Setting::set('paypal_enabled', $request->input('is_enabled'));
			Setting::save();
		}
		
		alert()->success('Successfully saved');
        return redirect()->route('panel.payments.index');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($listing)
    {
        $listing->delete();

        alert()->success('Successfully deleted');
        return redirect()->route('panel.listings.index');
    }
}
