<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\FileViewFinder;
use Theme;
use URL;
use Crypt;

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
        $this->app['translator']->addJsonPath(storage_path('app/resources/lang')); //now we can translate without changing the core

        try {
            \DB::connection()->getPdo();
        } catch (\Exception $e) {
            return;
        }

        if (!\App::runningInConsole() && \Schema::hasTable('settings')) {

            setting(['marketplace_index' => "home"]);

            if (setting('custom_homepage') || module_enabled('homepage')) {
                setting(['marketplace_index' => "browse"]);
            }

            if (setting('site_name')) {
                config(['app.name' => setting('site_name', env('APP_NAME', 'MarketplaceKit'))]);
                config(['app.url' => setting('site_url', url('/'))]);
            }

            if (setting('google_maps_key')) {
                config(['googlmapper.key' => setting('google_maps_key')]);
            } else {
                setting(['enable_geo_search' => false]);
                setting(['show_map' => false]);
                if (setting('default_view') == 'map' && !env("DEMO")) {
                    setting(['default_view' => 'grid']);
                }
                config(['googlmapper.key' => ""]);
            }

            config(['marketplace.stripe_publishable_key' => setting("stripe_publishable_key")]);
            config(['marketplace.stripe_secret_key' => setting("stripe_secret_key")]);

            if (setting('paypal_enabled')) {

                #paypal oauth
                $redirect_url = url("account/paypal/callback");
                if (setting('paypal_mode') == 'sandbox') {
                    config(['services.paypal_sandbox.client_id' => setting('paypal_client_id')]);
                    config(['services.paypal_sandbox.client_secret' => setting("paypal_client_secret")]);
                    config(['services.paypal_sandbox.redirect' => $redirect_url]);
                } else {
                    config(['services.paypal.client_id' => setting('paypal_client_id')]);
                    config(['services.paypal.client_secret' => setting("paypal_client_secret")]);
                    config(['services.paypal.redirect' => $redirect_url]);
                }
            }

            if (env("DEMO")) {
                setting(['google_maps_key' => env("DEMO_GOOGLE_MAPS_KEY")]);
                setting(['enable_geo_search' => true]);
                setting(['show_map' => true]);
                config(['googlmapper.key' => env("DEMO_GOOGLE_MAPS_KEY")]);


                setting(['paypal_enabled' => true]);
                setting(['paypal_mode' => 'sandbox']);
                setting(['paypal_email' => env("DEMO_PAYPAL_EMAIL")]);
                setting(['paypal_user' => env("DEMO_PAYPAL_USERNAME")]);
                setting(['paypal_password' => env("DEMO_PAYPAL_PASSWORD")]);
                setting(['paypal_signature' => env("DEMO_PAYPAL_SECRET")]);

                #paypal oauth
                config(['services.paypal_sandbox.client_id' => env('DEMO_PAYPAL_CLIENT_ID')]);
                config(['services.paypal_sandbox.client_secret' => env("DEMO_PAYPAL_CLIENT_SECRET")]);
                config(['services.paypal_sandbox.redirect' => secure_url('account/paypal/callback')]);

                setting(['stripe_publishable_key' => env("DEMO_STRIPE_PUBLISHABLE_KEY")]);
                setting(['stripe_secret_key' => env("DEMO_STRIPE_SECRET_KEY")]);
                setting(['facebook_key' => env("DEMO_FACEBOOK_KEY")]);
                setting(['facebook_secret' => env("DEMO_FACEBOOK_SECRET")]);

                config(['marketplace.stripe_publishable_key' => env("DEMO_STRIPE_PUBLISHABLE_KEY")]);
                config(['marketplace.stripe_secret_key' => env("DEMO_STRIPE_SECRET_KEY")]);

                config(['services.facebook.client_id' => env("DEMO_FACEBOOK_KEY")]);
                config(['services.facebook.client_secret' => env("DEMO_FACEBOOK_SECRET")]);
                config(['services.facebook.redirect' => url('login/facebook')]);

            }

            Theme::set(setting('theme', config('themes.default')));
            if (request('theme')) {
                Theme::set(request('theme'));
            }

        }

        if(request()->ip() == "127.0.0.1"){
            config(['geoip.random' => true]);
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
        #set theme
        config(['themes.themes.custom.views-path' => ('storage/app/themes/'.Theme::get())]);
        $this->app->singleton('view.finder', function($app) {
            $paths = $app['config']['view.paths'];
            array_unshift($paths, base_path(config('themes.themes.custom.views-path')));
            return new \Igaster\LaravelTheme\themeViewFinder(
                $app['files'],
                $paths,
                null
            );
        });
    }
}
