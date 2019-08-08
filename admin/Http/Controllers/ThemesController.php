<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use App\Models\PageTranslation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use Theme;
use Zip;
use File;
use Zipper;
use Illuminate\Support\Str;

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
		setting(['theme' => $theme_name])->save();

        Theme::set($theme_name);
        $manager = \App::make('Barryvdh\TranslationManager\Manager');
        $theme_path = resource_path("themes/".Theme::get());
        if(file_exists($theme_path)) {
            $manager->findTranslations($theme_path);
        }

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

            $zip = Zipper::make($request->file('theme'));

            $files = collect($zip->listFiles());
            $theme_json = $files->flatten()->filter(function($file) {
                return Str::endsWith($file, 'theme.json');
            })->first();
            $theme_info = json_decode($zip->getFileContent($theme_json));
            $theme_name = $theme_info->name;

            //check if theme exists
            $themes = \App::make('igaster.themes');
            $themes = collect(json_decode(json_encode($themes->scanJsonFiles())));

            $zip->folder($theme_name.'/public/themes/'.$theme_name)->extractTo(public_path('themes/'.$theme_name));
            $zip->folder($theme_name.'/resources/themes/'.$theme_name)->extractTo(resource_path('themes/'.$theme_name));

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
