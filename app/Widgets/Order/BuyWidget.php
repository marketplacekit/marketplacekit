<?php

namespace App\Widgets\Order;

use Arrilot\Widgets\AbstractWidget;

class BuyWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];
	
	public function calculate_price($listing, $params) {
        $fee_percentage = setting('marketplace_percentage_fee');
        $fee_transaction = setting('marketplace_transaction_fee');
		
		$quantity = isset($params['quantity'])?$params['quantity']:1;
		$variants = isset($params['variant'])?$params['variant']:null;
		$shipping = isset($params['shipping_option'])?$params['shipping_option']:null;
		$additional_options = isset($params['additional_option'])?$params['additional_option']:[];
		$additional_options_meta = isset($params['additional_options_meta'])?$params['additional_options_meta']:[];
		
		$listing_price = $listing->price;
		
		#calculate additional variant cost
		$selected_variant = null;
		$error = false;

        $user_choice = [];
        $user_choice[] = ['group' => 'general', 'name' => 'quantity', 'value' => $quantity];

        if($variants) {
			$variant_pricing = $listing->variants;
			foreach($variants as $k => $v) {
				$variant_pricing = $variant_pricing->where("meta.$k", $v);
                $user_choice[] = ['group' => 'variant', 'name' => $k, 'value' => $v];
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
            $user_choice[] = ['group' => 'shipping', 'name' => 'Shipping', 'value' => $selected_shipping_method->name, 'price' => $selected_shipping_method->price];
		}

		#additional pricing
        $additional_options_price = $listing->additional_options->reduce(function ($carry, $item) use($additional_options, $additional_options_meta) {
            if(in_array($item->id, array_keys($additional_options))) {
				$price = $item->price;
				$quantity = 1;
				if(in_array($item->id, array_keys($additional_options_meta)) && isset($additional_options_meta[$item->id]['quantity'])) {				
					$quantity = (int) $additional_options_meta[$item->id]['quantity'];
				}				
                return $carry + ($price*$quantity);
			}
						
            return $carry;
        }, 0);

		$number = 0;
		foreach($listing->additional_options as $k => $item) {
            if(in_array($item->id, array_keys($additional_options))) {
                $number++;
                $user_choice[] = ['group' => 'additional_options', 'name' => 'Option '.($k+1), 'value' => $item->name, 'price' => $item->price];
            }
        }
		
		//date, time, qty
		$subtotal = ($quantity * $listing_price) + $additional_options_price;
        $service_fee_percentage = $subtotal * ($fee_percentage/100);
        $service_fee = (float) $service_fee_percentage + (float) $fee_transaction;
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
				'price' => ($quantity * $listing_price)
			]
		];
		if($selected_shipping_price) {
			$price_items[] = [
				'key'	=> 'service',
				'label'	=> __('Shipping'),
				'price'	=> $selected_shipping_price,
			];
		}

        if($additional_options_price) {
            $price_items[] = [
                'key'	=> 'additional',
                'label'	=> __('Additional options'),
                'price'	=> $additional_options_price,
            ];
        }

        if($service_fee > 0) {
            $price_items[] = [
                'key' => 'service',
                'label' => __('Service fee'),
                'price' => $service_fee,
                'notice' => __('This fee helps cover the costs of operating the website'),
            ];
        }
		
		return [
			'user_choice'	=>	$user_choice,
			'error'			=>	$error,
			'total'			=>	$total,
			'service_fee'	=>	$service_fee,
			'price_items'	=>	$price_items,
		];
	
	}
	
	public function decrease_stock($order, $listing)
    {
        $quantity = $order->listing_options['quantity'];
        $listing->decrement( 'stock', $quantity );

        if(isset($order->listing_options['variant'])) {
            $variants = $order->listing_options['variant'];

            $listing_variants = $listing->variants;
            foreach($variants as $k => $v) {
                $listing_variants = $listing_variants->where("meta.$k", $v);
            }

            if($listing_variants->count() == 1) {
                $listing_variant = $listing_variants->first();
                $listing_variant->decrement( 'stock', $quantity );
            }
        }
	}

	public function validate_payment($listing, $request)
    {
		$result = $this->calculate_price($listing, request()->all());
		return $result;
	}

	
    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run($listing)
    {
        //
		$total = 0;
		$quantity = request('quantity', 1);

		$result = $this->calculate_price($listing, request()->all());
		
        return view('listing.widgets.buy_widget', [
            'config' 	    => $this->config,
            'listing' 	    => $listing,
            'qs' 	        => http_build_query(request()->all()),
            'error' 	    => $result['error'],
            'total' 	    => $result['total'],
            'service_fee' 	=> $result['service_fee'],
            'price_items' 	=> $result['price_items'],
        ]);
    }
}
