<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Locale;

class ConfigServiceProvider extends ServiceProvider {

    public function register()
    {
        $connection = null;
        $table = null;

        try {
            $connection = \DB::connection()->getPdo();
        } catch (\Exception $e) {
            #dd($e);
            return;
        }

        $table = \Schema::hasTable('settings');
        if($table) {
            $settings = \DB::connection()->table('settings')->get()->pluck('value', 'key')->toArray();
            $default_locale = isset($settings['default_locale']) ? $settings['default_locale'] : 'en';

            $supported_locales = [];
            foreach ($settings as $key => $value) {
                if (strpos($key, 'supported_locales.') !== false) {
                    list($k, $index) = explode('.', $key);
                    $supported_locales[(int) $index] = $value;
                }
            }

            if (empty($supported_locales))
                $supported_locales = ['en'];
            #dd($supported_locales);

            //first we set the languages
            if (true) {
                $language_options = [];
                foreach (language_options() as $language_option => $values) {
                    if (in_array($language_option, $supported_locales)) {
                        $language_options[$language_option] = $values;
                    }
                }
                if (count($language_options)) {
                    config(['laravellocalization.supportedLocales' => $language_options]);
                    config(['laravellocalization.hideDefaultLocaleInURL' => true]);
                }
            }

            if ($supported_locales && in_array($default_locale, $supported_locales)) {
                config(['app.locale' => $default_locale]);
            } else {
                reset($supported_locales);
                $default_locale = key($supported_locales);
                config(['app.locale' => $default_locale]);
            }



        }
    }

    /**
    * Register the service provider.
    *
    * @return void
    */
    public function boot()
    {
    //


    }

}