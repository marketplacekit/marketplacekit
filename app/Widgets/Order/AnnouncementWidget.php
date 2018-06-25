<?php

namespace App\Widgets\Order;

use Arrilot\Widgets\AbstractWidget;

class AnnouncementWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    public function calculate_price($listing, $params) {
        $fee_percentage = 5;

        $quantity = isset($params['quantity'])?$params['quantity']:1;
        $variants = isset($params['variant'])?$params['variant']:null;
        $shipping = isset($params['shipping_option'])?$params['shipping_option']:null;

        $listing_price = $listing->price;

        #calculate additional variant cost
        $selected_variant = null;
        $error = false;

        if($variants) {
            $variant_pricing = $listing->variants;
            foreach($variants as $k => $v) {
                $variant_pricing = $variant_pricing->where("meta.$k", $v);
            }

            if($variant_pricing->count() == 1) {
                $selected_variant = $variant_pricing->first();
                $listing_price += $selected_variant->price;
                if($quantity > $selected_variant->stock) {
                    $error = __('Insufficient stock. Please lower the quantity.');
                }
                if($selected_variant->stock < 1) {
                    $error = __('Out of Stock');
                }
            }
        }

        #calculate shipping cost
        $selected_shipping_price = null;
        if(!is_null($shipping)) {
            $selected_shipping_method = $listing->shipping_options->firstWhere('id', $shipping)?:null;
            if($selected_shipping_method) {
                $selected_shipping_price = $selected_shipping_method->price;
            }
        }

        //date, time, qty
        $subtotal = $quantity * $listing_price;
        $service_fee = $subtotal * ($fee_percentage/100);
        $total = $subtotal + $service_fee + $selected_shipping_price;

        if($quantity > $listing->stock) {
            $error = __('Insufficient stock. Please lower the quantity.');
        }
        if($listing->stock < 1) {
            $error = __('Out of Stock');
        }

        //now check if we have any slots left for this time
        $price_items = [
            [
                'key' 	=> 'price',
                'label' => __(':price x :quantity :unit_label', ['price' => format_money($listing_price, $listing->currency), 'quantity' => $quantity, 'unit_label' => $listing->unit]),
                'price' => $subtotal
            ],
            [
                'key'	=> 'service',
                'label' => 'Service fee',
                'price' => $service_fee
            ],
        ];
        if($selected_shipping_price) {
            $price_items[] = [
                'key'	=> 'service',
                'label'	=> 'Shipping',
                'price'	=> $selected_shipping_price,
            ];
        }

        return [
            'error'			=>	$error,
            'total'			=>	$total,
            'service_fee'	=>	$service_fee,
            'price_items'	=>	$price_items,
        ];

    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run($listing)
    {
        $total = 0;
        $quantity = request('quantity', 1);

        $result = $this->calculate_price($listing, request()->all());

        return view('listing.widgets.announcement_widget', [
            'config' 	=> $this->config,
            'listing' 	=> $listing,
            'error' 	=> $result['error'],
            'total' 	=> $result['total'],
            'service_fee' 	=> $result['service_fee'],
            'price_items' 	=> $result['price_items'],
        ]);
    }
}
