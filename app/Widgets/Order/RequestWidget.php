<?php

namespace App\Widgets\Order;

use Arrilot\Widgets\AbstractWidget;

class RequestWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    public function calculate_price($listing, $params) {
        $fee_percentage = 0;

        $quantity = isset($params['quantity'])?$params['quantity']:1;
        $variants = isset($params['variant'])?$params['variant']:null;
        $shipping = isset($params['shipping_option'])?$params['shipping_option']:null;

        $listing_price = $listing->price;

        #calculate additional variant cost
        $selected_variant = null;
        $error = false;

        //date, time, qty
        $subtotal = $quantity * $listing_price;
        $service_fee = $subtotal * ($fee_percentage/100);
        $total = $subtotal + $service_fee;

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

        return view('listing.widgets.request_widget', [
            'config' 	=> $this->config,
            'listing' 	=> $listing,
            'error' 	=> $result['error'],
            'total' 	=> $result['total'],
            'service_fee' 	=> $result['service_fee'],
            'price_items' 	=> $result['price_items'],
        ]);
    }
}
