<?php

use Illuminate\Database\Seeder;

class CategoryPricingModelTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('category_pricing_model')->delete();
        
        \DB::table('category_pricing_model')->insert(array (
            0 => 
            array (
                'category_id' => 1,
                'pricing_model_id' => 1,
            ),
        ));
        
        
    }
}