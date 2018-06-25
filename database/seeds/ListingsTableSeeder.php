<?php

use Illuminate\Database\Seeder;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Carbon\Carbon;
class ListingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('listings')->delete();
        
        \DB::table('listings')->insert(array (
            0 => 
    array (
        'id' => 1,
        'key' => NULL,
        'user_id' => 1,
        'category_id' => 1,
        'pricing_model_id' => 1,
        'title' => 'My First Listing',
        'blurb' => NULL,
        'photo' => NULL,
        'quantity' => 0,
        'stock' => 1,
        'photos' => '{"1":"https://marketplace-kit.s3.amazonaws.com/default_listing.jpg"}',
    'description' => '<p>Welcome to MarketPlaceKit. This is your first listing. Edit or delete it, then start add listings!</p><p><br></p><p class="ql-align-justify">"The buyer is entitled to a bargain. The seller is entitled to a profit. So there is a fine margin in between where the price is right. I have found this to be true to this day whether dealing in paper hats, winter underwear or hotels."</p><p class="ql-align-justify">-  <span>Conrad Hilton</span></p>',
'spotlight' => NULL,
                'staff_pick' => NULL,
                'views_count' => NULL,
                'unit' => NULL,
                'min_units' => 1,
                'max_units' => NULL,
                'min_duration' => NULL,
                'max_duration' => NULL,
                'session_duration' => NULL,
                'pricing_models' => 'buy',
                'price' => '15.00',
                'currency' => 'GBP',
        'location' => \DB::raw("ST_GeomFromText('POINT(-0.10213410 51.509865)')"),
        'lat' => '51.61100420',
        'lng' => '-0.10213410',
        'meta' => '[]',
		'city' => 'London',
		'country' => 'UK',
		'seller_type' => NULL,
		'variant_options' => '{"Primary colour": ["Blue", "Red", "Green"], "Size": ["XL", "XS", "SM"]}',
		'vendor' => NULL,
		'timeslots' => NULL,
		'tags' => NULL,
		'tags_string' => NULL,
		'units_in_product_display' => NULL,
		'price_per_unit_display' => NULL,
		'locale' => 'en',
		'is_published' => 1,
		'is_admin_verified' => Carbon::now()->format('Y-m-d H:i:s'),
		'is_disabled' => NULL,
        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
		'deleted_at' => NULL,
    ),
));
        
        
    }
}