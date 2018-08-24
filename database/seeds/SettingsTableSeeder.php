<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('settings')->delete();
        
        \DB::table('settings')->insert(array (
            
            array (
                'id' => 3,
                'key' => 'site_name',
                'value' => 'MarketplaceKit',
            ),
            
            array (
                'id' => 5,
                'key' => 'theme',
                'value' => 'default',
            ),
            
            array (
                'id' => 6,
                'key' => 'currency',
                'value' => 'GBP',
            ),
            
            array (
                'id' => 7,
                'key' => 'name',
                'value' => 'Request',
            ),
            
            array (
                'id' => 8,
                'key' => 'widget',
                'value' => 'buy',
            ),
            
            array (
                'id' => 9,
                'key' => 'unit_name',
                'value' => 'property',
            ),
            
            array (
                'id' => 10,
                'key' => 'duration_name',
                'value' => '',
            ),
            
            array (
                'id' => 11,
                'key' => 'can_add_pricing',
                'value' => '1',
            ),
            
            array (
                'id' => 13,
                'key' => 'default_pricing_model',
                'value' => '4',
            ),
            
            array (
                'id' => 14,
                'key' => 'home_title',
                'value' => 'Home',
            ),
            
            array (
                'id' => 15,
                'key' => 'home_description',
                'value' => '',
            ),
            
            array (
                'id' => 16,
                'key' => 'site_title',
                'value' => 'Title',
            ),
            
            array (
                'id' => 19,
                'key' => 'show_map',
                'value' => '1',
            ),
            
            array (
                'id' => 20,
                'key' => 'show_list',
                'value' => '1',
            ),
            
            array (
                'id' => 21,
                'key' => 'show_grid',
                'value' => '1',
            ),
            
            array (
                'id' => 22,
                'key' => 'default_view',
                'value' => 'grid',
            ),
            
            array (
                'id' => 23,
                'key' => 'site_description',
                'value' => 'Description',
            ),
            
            array (
                'id' => 28,
                'key' => 'site_logo',
                'value' => null,
            ),
            
            array (
                'id' => 29,
                'key' => 'distance_unit',
                'value' => 'miles',
            ),
            
            array (
                'id' => 30,
                'key' => 'default_locale',
                'value' => 'en',
            ),
            
            array (
                'id' => 31,
                'key' => 'supported_locales.0',
                'value' => 'en',
            ),
            
            array (
                'id' => 35,
                'key' => 'listings_require_verification',
                'value' => '0',
            ),
            
            array (
                'id' => 36,
                'key' => 'site_url',
                'value' => '/',
            ),
            
            array (
                'id' => 37,
                'key' => 'enable_geo_search',
                'value' => '1',
            ),
            
            array (
                'id' => 38,
                'key' => 'marketplace_transaction_fee',
                'value' => '3',
            ),
            
            array (
                'id' => 39,
                'key' => 'marketplace_percentage_fee',
                'value' => '20',
            ),
            
            array (
                'id' => 40,
                'key' => 'email_address',
                'value' => '',
            ),
            
            array (
                'id' => 41,
                'key' => 'paypal_email',
                'value' => '',
            ),
            
            array (
                'id' => 42,
                'key' => 'paypal_username',
                'value' => '',
            ),
            
            array (
                'id' => 43,
                'key' => 'paypal_password',
                'value' => '',
            ),
            
            array (
                'id' => 44,
                'key' => 'paypal_signature',
                'value' => '',
            ),
            
            array (
                'id' => 45,
                'key' => 'paypal_mode',
                'value' => 'sandbox',
            ),
            
            array (
                'id' => 59,
                'key' => 'google_analytics_key',
                'value' => NULL,
            ),
            
            array (
                'id' => 60,
                'key' => 'google_maps_key',
                'value' => NULL,
            ),
            
            array (
                'id' => 61,
                'key' => 'stripe_publishable_key',
                'value' => NULL,
            ),
            
            array (
                'id' => 62,
                'key' => 'facebook_key',
                'value' => NULL,
            ),
            
            array (
                'id' => 63,
                'key' => 'facebook_secret',
                'value' => NULL,
            ),
            
            array (
                'id' => 64,
                'key' => 'custom_homepage',
                'value' => '0',
            ),
            
            array (
                'id' => 65,
                'key' => 'show_search_sidebar',
                'value' => '0',
            ),
            
            array (
                'id' => 66,
                'key' => 'stripe_secret_key',
                'value' => NULL,
            ),

            array (
                'id' => 67,
                'key' => 'moderatelistings.report_types.0.value',
                'value' => 'Inappropriate',
            ),
            array (
                'id' => 68,
                'key' => 'moderatelistings.report_types.1.value',
                'value' => 'Duplicate',
            ),
            array (
                'id' => 69,
                'key' => 'moderatelistings.report_types.2.value',
                'value' => 'Spam',
            ),
        ));
        
        
    }
}