<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class FiltersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('filters')->delete();
        
        \DB::table('filters')->insert(array (
            1 => 
				array (
					'id' => 1,
					'position' => 3,
					'name' => 'Price',
					'field' => 'price',
					'search_ui' => 'priceRange',
					'form_input_type' => NULL,
					'form_input_meta' => NULL,
					'is_category_specific' => 0,
					'is_searchable' => 1,
					'is_hidden' => 0,
					'is_default' => 1,
					'categories' => NULL,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
				),
            2 => 
				array (
					'id' => 3,
					'position' => 1,
					'name' => 'Distance',
					'field' => 'distance',
					'search_ui' => 'distance',
					'form_input_type' => NULL,
					'form_input_meta' => NULL,
					'is_category_specific' => 0,
					'is_searchable' => 1,
					'is_hidden' => 0,
					'is_default' => 1,
					'categories' => NULL,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
				),
			3 =>
				array (
					'id' => 2,
					'position' => 2,
					'name' => 'Categories',
					'field' => 'category_id',
					'search_ui' => 'category',
					'form_input_type' => NULL,
					'form_input_meta' => NULL,
					'is_category_specific' => NULL,
					'is_searchable' => 1,
					'is_hidden' => 0,
					'is_default' => 1,
					'categories' => '[]',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
				),
        ));
        
        
    }
}