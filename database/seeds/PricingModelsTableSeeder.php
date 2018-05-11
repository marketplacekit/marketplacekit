<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class PricingModelsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('pricing_models')->delete();
        
        \DB::table('pricing_models')->insert(array (

            array (
                'id' => 1,
                'name' => 'List something',
                'seller_label' => 'Post an announcement',
                'widget' => 'request',
                'unit_name' => 'unit',
                'duration_name' => NULL,
                'price_display' => 'unit',
                'breakdown_display' => 'unit',
                'quantity_label' => 'quantity',
                'can_accept_payments' => 0,
                'can_add_variants' => 0,
                'can_add_shipping' => 0,
                'can_add_pricing' => 0,
                'can_add_additional_pricing' => 0,
                'requires_shipping_address' => 0,
                'requires_billing_address' => 0,
                'meta' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ),

        ));
        
        
    }
}