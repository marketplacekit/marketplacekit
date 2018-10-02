<?php

use Illuminate\Database\Seeder;

class PlansTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('plans')->delete();
        
        \DB::table('plans')->insert(array (
            
            array (
                'name' => 'Free',
                'description' => NULL,
                'price' => '0.00',
                'interval' => 'month',
                'interval_count' => 1,
                'trial_period_days' => 0,
                'sort_order' => NULL,
                'created_at' => '2018-09-11 15:28:59',
                'updated_at' => '2018-09-19 19:55:48',
            ),
            
            array (
                'name' => 'Standard',
                'description' => NULL,
                'price' => '14.99',
                'interval' => 'month',
                'interval_count' => 1,
                'trial_period_days' => 0,
                'sort_order' => NULL,
                'created_at' => '2018-09-11 15:29:28',
                'updated_at' => '2018-09-11 15:29:28',
            ),
            
            array (
                'name' => 'Business',
                'description' => NULL,
                'price' => '49.99',
                'interval' => 'month',
                'interval_count' => 1,
                'trial_period_days' => 0,
                'sort_order' => NULL,
                'created_at' => '2018-09-11 15:29:52',
                'updated_at' => '2018-09-11 15:29:52',
            ),
        ));
        
        
    }
}