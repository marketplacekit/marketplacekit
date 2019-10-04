<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use App\Models\PageTranslation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $pages = new PageTranslation();
        $data = [];
        $data['selected_lang'] = $request->get('locale', app('laravellocalization')->getDefaultLocale());
        $pages = $pages->where('locale', '=', $data['selected_lang']);
        $data['pages'] = $pages->paginate(10);

        return view('panel::pages.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(FormBuilder $formBuilder)
    {
        $form = $formBuilder->create('Modules\Panel\Forms\PageForm', [
            'method' => 'POST',
            'url' => route('panel.pages.store'),
        ]);
        return view('panel::pages.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $page = new PageTranslation();
        $page->fill($request->all());
        $page->save();

        alert()->success('Successfully saved');
        return redirect()->route('panel.pages.index', ['locale' => $page->locale]);
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
        $page = PageTranslation::findOrFail($id);
        $form = $formBuilder->create('Modules\Panel\Forms\PageForm', [
            'method' => 'PUT',
            'url' => route('panel.pages.update', $id),
            'model' => $page
        ]);
        return view('panel::pages.create', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $page = PageTranslation::findOrFail($id);
        $page->fill($request->all());
        $page->save();

        alert()->success('Successfully saved');
        return redirect()->route('panel.pages.index', ['locale' => $page->locale]);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        $page = PageTranslation::findOrFail($id);
        $page->delete();
        alert()->success('Successfully deleted');
        return redirect()->route('panel.pages.index', ['locale' => $page->locale]);
    }
}
