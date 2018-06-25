<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use App\Models\PageTranslation;
use App\Models\Widget;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Kris\LaravelFormBuilder\FormBuilder;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        dd(5);
        $data = [];
        $data['selected_lang'] = $request->get('locale', app('laravellocalization')->getDefaultLocale());
        $data['widgets'] = Widget::where('locale', $data['selected_lang'])->orderBy('position', 'ASC')->get();
        $data['form_ui'] = [
            'paragraph'         =>   'Paragraph',
            'hero'              =>   'Hero',
            'video'             =>   'Video',
            'category_listing'  =>   'Category Listing',
            'latest_listings'   =>   'Latest Listings',
            'featured_listings'  =>  'Featured Listings',
            'popular_listings'  =>   'Popular Listings',
            'image_gallery'     =>   'Image Gallery'
        ];
        return view('panel::home.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(FormBuilder $formBuilder)
    {
        $form = $formBuilder->create('Modules\Panel\Forms\WidgetForm', [
            'method' => 'POST',
            'url' => route('panel.home.store'),
        ]);
        return view('panel::home.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $params = $request->all();
        $params['metadata'] = json_decode($params['metadata_str'], true)['values'];

        $widget = new Widget();
        $widget->title = $request->get('title');
        $widget->alignment = $request->get('alignment');
        $widget->position = $request->get('position', 1000);
        $widget->locale = $request->get('locale');
        $widget->type = $request->get('type');
        $widget->background =  $request->get('background');
        $widget->style =  $request->get('style');
        $widget->metadata =  $params['metadata'];
        $widget->save();

        alert()->success('Successfully saved');
        return redirect()->route('panel.home.index', ['locale' => $widget->locale]);
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
        $widget = Widget::findOrFail($id);
        $form = $formBuilder->create('Modules\Panel\Forms\WidgetForm', [
            'method' => 'PUT',
            'url' => route('panel.home.update', $id),
            'model' => $widget
        ]);

        return view('panel::home.create', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $params = $request->all();
        $params['metadata'] = json_decode($params['metadata_str'], true)['values'];

        $widget = Widget::findOrFail($id);
        $widget->title = $request->get('title');
        $widget->alignment = $request->get('alignment');
        $widget->position = $request->get('position', 1000);
        $widget->type = $request->get('type');
        $widget->background =  $request->get('background');
        $widget->style =  $request->get('style');
        $widget->metadata =  $params['metadata'];
        $widget->save();

        alert()->success('Successfully saved');
        return redirect()->route('panel.home.index', ['locale' => $widget->locale]);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
