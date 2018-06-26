<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use App\Models\PageTranslation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use Igaster\LaravelTheme\Facades\Theme;
use DotenvEditor;
use Zip;
use File;
use Zipper;

class ThemesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $themes = \App::make('igaster.themes');
        $data['themes'] = json_decode(json_encode($themes->scanJsonFiles()));
        if(!count($data['themes'])) {
            alert()->danger('You have no themes installed.');
        }
        return view('panel::themes.index', $data);
    }

    public function toggle($theme_name, Request $request)
    {
        DotenvEditor::setKey('THEME', $theme_name);
        DotenvEditor::save();
        return redirect('/panel/themes');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data = [];
        return view('panel::themes.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        #$theme_name = str_slug(pathinfo($request->theme->getClientOriginalName(), PATHINFO_FILENAME), '_');
        try {

            $theme_info = json_decode(Zipper::make($request->file('theme'))->getFileContent('theme/theme.json'));
            $theme_name = $theme_info->name;

            //check if theme exists
            $themes = \App::make('igaster.themes');
            $themes = collect(json_decode(json_encode($themes->scanJsonFiles())));

            if(Zipper::make($request->file('theme'))->contains('public/'))
                Zipper::make($request->file('theme'))->folder('public')->extractTo(public_path('themes/'.$theme_name));

            if(Zipper::make($request->file('theme'))->contains('theme/'))
                Zipper::make($request->file('theme'))->folder('theme')->extractTo(resource_path('themes/'.$theme_name));

            alert()->success("Successfully added $theme_name theme");

        } catch (\Exception $e) {
            alert()->danger("Error: ". $e->getMessage());
            return redirect()->route('panel.themes.create');
        }


        return redirect()->route('panel.themes.index');
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
    public function destroy()
    {
    }
}
