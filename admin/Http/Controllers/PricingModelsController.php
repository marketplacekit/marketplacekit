<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use Setting;
use App\Models\PricingModel;

class PricingModelsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, FormBuilder $formBuilder)
    {
        $pricing_models = PricingModel::get();
        $this->save_languages();
        return view('panel::pricing-models.index', compact('pricing_models'));
    }


    public function save_languages()
    {
        $pricing_models = PricingModel::get();
        $strings = [];
        foreach($pricing_models as $pricing_model) {
            $strings[] = $pricing_model->name;
            $strings[] = $pricing_model->seller_label;
            $strings[] = $pricing_model->unit_name;
            $strings[] = $pricing_model->unit_name.'_plural';
            $strings[] = $pricing_model->duration_name;
            $strings[] = $pricing_model->duration_name.'_plural';
        }

        save_language_file('pricing_models', $strings);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $pricing_model = new PricingModel();
        return view('panel::pricing-models.create', compact('pricing_model'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $pricing_model = new PricingModel();
        $params = $request->only(["name", "widget", "unit_name", "duration_name"]);
        $pricing_model->fill($params);

        $pricing_model->seller_label = $request->input('seller_label');
        $pricing_model->can_add_pricing = $request->has('can_add_pricing');
        $pricing_model->can_accept_payments = $request->has('can_accept_payments');
        $pricing_model->can_add_variants = $request->has('can_add_variants');
        $pricing_model->can_add_shipping = $request->has('can_add_shipping');
        $pricing_model->can_add_additional_pricing = $request->has('can_add_additional_pricing');

        if($pricing_model->widget == 'book_time') {
            $pricing_model->duration_name = "session";
        }

        if($pricing_model->widget == 'book_date' && !in_array($pricing_model->duration_name, ["day", "night"])) {
            $pricing_model->duration_name = "day";
        }
		if(!$pricing_model->widget) {
			$pricing_model->widget = 'buy';
		}
        $pricing_model->save();

        alert()->success('Successfully saved');
        return redirect()->route('panel.pricing-models.index');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('panel::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $pricing_model = PricingModel::find($id);
        return view('panel::pricing-models.create', compact('pricing_model'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update($id, Request $request)
    {
        $pricing_model = PricingModel::find($id);
        $params = $request->only(["name", "widget", "unit_name", "duration_name"]);
        $pricing_model->fill($params);

		$pricing_model->seller_label = $request->input('seller_label');
		$pricing_model->quantity_label = $request->input('quantity_label');
        $pricing_model->can_add_pricing = $request->has('can_add_pricing');
        $pricing_model->can_accept_payments = $request->has('can_accept_payments');
        $pricing_model->can_add_variants = $request->has('can_add_variants');
        $pricing_model->can_add_shipping = $request->has('can_add_shipping');
        $pricing_model->can_add_additional_pricing = $request->has('can_add_additional_pricing');
        $pricing_model->requires_shipping_address = $request->has('requires_shipping_address');
        $pricing_model->requires_billing_address = $request->has('requires_billing_address');
        $pricing_model->can_list_multiple_services = $request->has('can_list_multiple_services');
		
				
		$pricing_model->meta->per_label_buyer = $request->input('per_label_buyer');
		$pricing_model->meta->quantity_label_buyer = $request->input('quantity_label_buyer');
		$pricing_model->meta->can_seller_enter_per_label = $request->has('can_seller_enter_per_label');
		

        if($pricing_model->widget == 'book_time') {
            $pricing_model->duration_name = "session";
        }
        if($pricing_model->widget == 'book_date' && in_array($request->input('duration_name'), ["day", "night"])) {
            $pricing_model->duration_name = $request->input('duration_name');
        }
        $pricing_model->save();

        alert()->success('Successfully saved');
        return redirect()->route('panel.pricing-models.index');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
