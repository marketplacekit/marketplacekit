<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use Setting;
use Crypt;
use Config;
#use clagiordano\weblibs\configmanager\ConfigManager;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, FormBuilder $formBuilder)
    {
        $settings = Setting::all();
        #dd( setting('show_list') );
        #dd( Setting::all() );
        #dd( $settings );
        $form = $formBuilder->create('Modules\Panel\Forms\GeneralSettingsForm', [
            'method' => 'POST',
            'url' => route('panel.settings.store'),
        ], $settings);
        return view('panel::settings.index', compact('form'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('panel::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function sync() {
        $settings = Setting::all();

        #no need to sync anymore, all dynamic 18/07/2018

        $supported_locales = config('laravellocalization.supportedLocales');
        if((array_keys($supported_locales) != setting('supported_locales'))) {

            $language_options = [];
            foreach($this->language_options() as $language_option => $values) {
                if( in_array($language_option, setting('supported_locales') )) {
                    $language_options[$language_option] = $values;
                }
            }

            if(count($language_options)) {
                /*$config = new ConfigManager(base_path("config/laravellocalization.php"));
                $value = $config->getValue('supportedLocales');
                $config->setValue('supportedLocales', $language_options);
                $config->setValue('hideDefaultLocaleInURL', true);
                $config->saveConfigFile();*/
            }
        }

        #DotenvEditor::setKey('APP_NAME', setting('site_name'));
        #DotenvEditor::setKey('APP_URL', setting('site_url'));

        if($supported_locales && isset($supported_locales[setting('default_locale')])) {
            #DotenvEditor::setKey('APP_LOCALE', setting('default_locale'));
        } else {
            reset($supported_locales);
            $default_locale = key($supported_locales);
            Setting::set('default_locale', $default_locale);
            Setting::save();
            #DotenvEditor::setKey('APP_LOCALE', $default_locale);
        }
        #DotenvEditor::setKey('GOOGLE_MAPS_KEY', setting('google_maps_key'));
        #DotenvEditor::save();

    }

    public function remove(Request $request) {
        if ($request->has('site_logo')) {
            Setting::forget('site_logo');
            Setting::save();
            alert()->success('Successfully saved');
            return redirect()->route('panel.settings.index');
        }
    }

    public function store(Request $request)
    {


        if ($request->hasFile('site_logo')) {
            $filename = "logo." . $request->site_logo->getClientOriginalExtension();
            $request->site_logo->storeAs('images', $filename, 'public');
            Setting::set('site_logo', $filename);
        }

        if($request->has('supported_locales')) {
            Setting::set('supported_locales', $request->input('supported_locales'));
        }

        foreach($request->except(['_token', 'name', 'stripe_secret_key', 'site_logo', 'supported_locales', 'listings_require_verification', 'enable_geo_search', 'custom_homepage', 'show_search_sidebar']) as $key => $input) {
            Setting::set($key, $input);
        }
        Setting::save();
        $checkboxes = ['show_map', 'show_grid', 'show_list', 'listings_require_verification', 'enable_geo_search', 'custom_homepage', 'show_search_sidebar', 'single_listing_per_user'];
        #dd($request->has('show_list'));
        foreach($checkboxes as $checkbox) {
            Setting::set($checkbox, $request->has($checkbox));
        }
        Setting::save();

        $this->sync();

        alert()->success('Successfully saved');
        return redirect()->route('panel.settings.index');
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
    public function edit()
    {
        return view('panel::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }

    function language_options() {
        return language_options();
    }
}
