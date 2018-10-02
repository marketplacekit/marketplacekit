<?php

use Illuminate\Database\Seeder;

class ListingPlansTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('listing_plans')->delete();
        
        \DB::table('listing_plans')->insert(array (
            
            array (
                'name' => 'Free',
                'group' => NULL,
                'description' => NULL,
                'price' => '0.00',
                'credits' => 0,
                'duration_units' => 1,
                'duration_period' => 'week',
                'images' => 1,
                'spotlight' => 1,
                'priority' => 1,
                'bold' => 0,
                'category_id' => NULL,
                'min_price' => NULL,
                'max_price' => NULL,
                'meta' => NULL,
                'created_at' => '2018-09-10 15:39:04',
                'updated_at' => '2018-09-19 15:14:32',
                'deleted_at' => NULL,
            ),
            
            array (
                'name' => 'Standard',
                'group' => NULL,
                'description' => '2x more views than Basic adverts',
                'price' => '9.95',
                'credits' => 90,
                'duration_units' => 3,
                'duration_period' => 'week',
                'images' => 20,
                'spotlight' => 1,
                'priority' => 0,
                'bold' => 0,
                'category_id' => NULL,
                'min_price' => NULL,
                'max_price' => NULL,
                'meta' => NULL,
                'created_at' => '2018-09-10 15:40:49',
                'updated_at' => '2018-09-19 15:02:35',
                'deleted_at' => NULL,
            ),
            
            array (
                'name' => 'Premium',
                'group' => NULL,
                'description' => '4x more views than Basic adverts',
                'price' => '19.95',
                'credits' => 180,
                'duration_units' => 6,
                'duration_period' => 'week',
                'images' => 20,
                'spotlight' => 1,
                'priority' => 1,
                'bold' => 0,
                'category_id' => NULL,
                'min_price' => NULL,
                'max_price' => NULL,
                'meta' => NULL,
                'created_at' => '2018-09-19 10:35:56',
                'updated_at' => '2018-09-19 15:03:43',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}