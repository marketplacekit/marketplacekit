<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        if (!\App::runningInConsole() && \Schema::hasTable('settings')) {

            setting(['marketplace_index' => "home"]);
        
            if (setting('custom_homepage')) {
                setting(['marketplace_index' => "browse"]);
            }

            if (!setting('google_maps_key')) {
                setting(['enable_geo_search' => false]);
                setting(['show_map' => false]);
                if (setting('default_view') == 'map' && !env("DEMO")) {
                    setting(['default_view' => 'grid']);
                }
                config(['googlmapper.key' => ""]);
            }

            if(env("DEMO")) {
                setting(['google_maps_key' => env("DEMO_GOOGLE_MAPS_KEY")]);
                config(['googlmapper.key' => env("DEMO_GOOGLE_MAPS_KEY")]);
            }
        }

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //

    }
}
