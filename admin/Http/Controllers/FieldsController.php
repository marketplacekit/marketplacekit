<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use Setting;
use App\Models\Filter;

class FieldsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, FormBuilder $formBuilder)
    {
        $filters = Filter::orderBy('position', 'ASC')->get();
        return view('panel::fields.index', compact('filters'));
    }


    public function save_languages()
    {
        $filters = Filter::orderBy('position', 'ASC')->get();
        save_language_file('filters', $filters->pluck('name'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(FormBuilder $formBuilder)
    {
        //

        //php artisan make:form Forms/FilterForm --fields="name:text, field:text, form_ui:select, categories:select, hidden:checkbox, default:checkbox"
        $form = $formBuilder->create('Modules\Panel\Forms\FilterForm', [
            'method' => 'POST',
            'url' => route('panel.fields.store')
        ]);
        return view('panel::fields.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $params = $request->all();
        #dd($params);
        #$params['form_input_meta'] = json_encode(json_decode($params['form_input_meta'], true)[0]);

        $filter = new Filter();
        $filter->name = $request->get('name');
        $filter->field = $request->get('field');
        $filter->position = intval($request->get('position', 1000));
        $filter->is_category_specific = $request->get('is_category_specific');
        $filter->is_searchable =  $request->get('is_searchable');
        $filter->is_hidden =  0;
		$filter->categories = [];
		if($request->get('categories'))
			$filter->categories =  array_map('intval', $request->get('categories'));
        $filter->search_ui =  $request->get('search_ui');
        #$filter->form_input_meta = $params['form_input_meta'];
        $filter->form_input_type =  $request->get('form_input_type', 'text');
        $filter->save();

        $this->save_languages();
        
		alert()->success('Successfully saved');
        return redirect(route('panel.fields.edit', $filter->id));
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
    public function edit($id, FormBuilder $formBuilder)
    {
		
		$filter = Filter::findOrFail($id);
        $form = $formBuilder->create('Modules\Panel\Forms\FilterForm', [
            'method' => 'PUT',
            'url' => route('panel.fields.update', $id),
            'model' => $filter
        ]);

        return view('panel::fields.create', compact('form', 'filter'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update($id, Request $request)
    {
        $params = $request->all();
		
        $params['form_input_meta'] = (json_decode($params['form_input_meta_str'], true)[0]);
		#dd($params['form_input_meta']);
        #$params['form_input_meta'] = $params['form_input_meta'];

        $filter = Filter::findOrFail($id);
        $filter->position = $request->get('position', 1000);
        $filter->is_category_specific = $request->has('is_category_specific');
        $filter->is_searchable =  $request->has('is_searchable');
        $filter->is_hidden =  $request->has('is_hidden');
		$filter->categories = [];
		if($request->get('categories'))
			$filter->categories =  array_map('intval', $request->get('categories'));
        $filter->search_ui =  $request->get('search_ui');
        $filter->form_input_meta = $params['form_input_meta'];
        $filter->form_input_type =  $request->get('form_input_type');
        $filter->save();

        $this->save_languages();
        
		alert()->success('Successfully saved');
        return redirect(route('panel.fields.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
		$filter = Filter::findOrFail($id);
		$filter->delete();
		alert()->success('Successfully deleted');
        return redirect(route('panel.fields.index'));
    }
}
