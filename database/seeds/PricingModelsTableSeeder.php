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
                'can_list_multiple_services' => 0,
                'requires_shipping_address' => 0,
                'requires_billing_address' => 0,
                'meta' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ),

            array (
                'id' => 2,
                'name' => 'Buy',
                'seller_label' => 'Sell something',
                'widget' => 'buy',
                'unit_name' => 'item',
                'duration_name' => NULL,
                'price_display' => 'unit',
                'breakdown_display' => 'unit',
                'quantity_label' => 'quantity',
                'can_accept_payments' => 1,
                'can_add_variants' => 1,
                'can_add_shipping' => 1,
                'can_add_pricing' => 1,
                'can_add_additional_pricing' => 1,
                'can_list_multiple_services' => 0,
                'requires_shipping_address' => 0,
                'requires_billing_address' => 0,
                'meta' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ),
            
            array (
                'id' => 3,
                'name' => 'Book Room',
                'seller_label' => 'Rent room',
                'widget' => 'book_date',
                'unit_name' => 'room',
                'duration_name' => 'night',
                'price_display' => 'unit',
                'breakdown_display' => 'unit',
                'quantity_label' => 'Rooms',
                'can_accept_payments' => 1,
                'can_add_variants' => 0,
                'can_add_shipping' => 0,
                'can_add_pricing' => 1,
                'can_add_additional_pricing' => 1,
                'can_list_multiple_services' => 0,
                'requires_shipping_address' => 0,
                'requires_billing_address' => 0,
                'meta' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ),
            
            array (
                'id' => 4,
                'name' => 'Book Session',
                'seller_label' => 'List your service',
                'widget' => 'book_time',
                'unit_name' => 'place',
                'duration_name' => 'session',
                'price_display' => 'duration',
                'breakdown_display' => 'unit',
                'quantity_label' => 'Spaces per session',
                'can_accept_payments' => 0,
                'can_add_variants' => 0,
                'can_add_shipping' => 0,
                'can_add_pricing' => 0,
                'can_add_additional_pricing' => 0,
                'can_list_multiple_services' => 0,
                'requires_shipping_address' => 0,
                'requires_billing_address' => 0,
                'meta' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ),

            
            array (
                'id' => 5,
                'name' => 'Rent Item',
                'seller_label' => 'Rent an item',
                'widget' => 'book_date',
                'unit_name' => 'item',
                'duration_name' => 'day',
                'price_display' => 'duration',
                'breakdown_display' => 'unit',
                'quantity_label' => 'inventory',
                'can_accept_payments' => 1,
                'can_add_variants' => 0,
                'can_add_shipping' => 0,
                'can_add_pricing' => 1,
                'can_add_additional_pricing' => 1,
                'can_list_multiple_services' => 0,
                'requires_shipping_address' => 0,
                'requires_billing_address' => 0,
                'meta' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ),
            
            array (
                'id' => 6,
                'name' => 'Request',
                'seller_label' => 'Request',
                'widget' => 'request',
                'unit_name' => NULL,
                'duration_name' => NULL,
                'price_display' => 'unit',
                'breakdown_display' => 'unit',
                'quantity_label' => 'quantity',
                'can_accept_payments' => 0,
                'can_add_variants' => 0,
                'can_add_shipping' => 0,
                'can_add_pricing' => 0,
                'can_add_additional_pricing' => 0,
                'can_list_multiple_services' => 0,
                'requires_shipping_address' => 0,
                'requires_billing_address' => 0,
                'meta' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ),
        ));
        
        
    }
}