<?php

use Illuminate\Database\Seeder;

class PlanFeaturesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('plan_features')->delete();

        \DB::table('plan_features')->insert(array (

            array (
                'plan_id' => 1,
                'code' => 'listings',
                'value' => '1',
                'sort_order' => 1,
                'created_at' => '2018-09-11 15:28:59',
                'updated_at' => '2018-09-11 15:28:59',
            ),

            array (
                'plan_id' => 1,
                'code' => 'images',
                'value' => '1',
                'sort_order' => 5,
                'created_at' => '2018-09-11 15:28:59',
                'updated_at' => '2018-09-11 15:28:59',
            ),

            array (
                'plan_id' => 1,
                'code' => 'featured_listings',
                'value' => '0',
                'sort_order' => 15,
                'created_at' => '2018-09-11 15:28:59',
                'updated_at' => '2018-09-11 15:28:59',
            ),

            array (
                'plan_id' => 1,
                'code' => 'messages',
                'value' => '3',
                'sort_order' => 20,
                'created_at' => '2018-09-11 15:28:59',
                'updated_at' => '2018-09-11 15:28:59',
            ),

            array (
                'plan_id' => 1,
                'code' => 'bold_listings',
                'value' => '0',
                'sort_order' => 25,
                'created_at' => '2018-09-11 15:28:59',
                'updated_at' => '2018-09-11 15:28:59',
            ),

            array (
                'plan_id' => 2,
                'code' => 'listings',
                'value' => '10',
                'sort_order' => 1,
                'created_at' => '2018-09-11 15:29:28',
                'updated_at' => '2018-09-11 15:29:28',
            ),

            array (
                'plan_id' => 2,
                'code' => 'images',
                'value' => '10',
                'sort_order' => 5,
                'created_at' => '2018-09-11 15:29:28',
                'updated_at' => '2018-09-11 15:29:28',
            ),

            array (
                'plan_id' => 2,
                'code' => 'featured_listings',
                'value' => '2',
                'sort_order' => 15,
                'created_at' => '2018-09-11 15:29:28',
                'updated_at' => '2018-09-11 15:29:28',
            ),

            array (
                'plan_id' => 2,
                'code' => 'messages',
                'value' => '30',
                'sort_order' => 20,
                'created_at' => '2018-09-11 15:29:28',
                'updated_at' => '2018-09-11 15:29:28',
            ),

            array (
                'plan_id' => 2,
                'code' => 'bold_listings',
                'value' => '2',
                'sort_order' => 25,
                'created_at' => '2018-09-11 15:29:28',
                'updated_at' => '2018-09-11 15:29:28',
            ),

            array (
                'plan_id' => 3,
                'code' => 'listings',
                'value' => '100',
                'sort_order' => 1,
                'created_at' => '2018-09-11 15:29:52',
                'updated_at' => '2018-09-11 15:30:03',
            ),

            array (
                'plan_id' => 3,
                'code' => 'images',
                'value' => '20',
                'sort_order' => 5,
                'created_at' => '2018-09-11 15:29:52',
                'updated_at' => '2018-09-11 15:29:52',
            ),

            array (
                'plan_id' => 3,
                'code' => 'featured_listings',
                'value' => '10',
                'sort_order' => 15,
                'created_at' => '2018-09-11 15:29:52',
                'updated_at' => '2018-09-11 15:29:52',
            ),

            array (
                'plan_id' => 3,
                'code' => 'messages',
                'value' => '300',
                'sort_order' => 20,
                'created_at' => '2018-09-11 15:29:52',
                'updated_at' => '2018-09-11 15:29:52',
            ),

            array (
                'plan_id' => 3,
                'code' => 'bold_listings',
                'value' => '10',
                'sort_order' => 25,
                'created_at' => '2018-09-11 15:29:52',
                'updated_at' => '2018-09-11 15:29:52',
            ),
        ));


    }
}