<?php

namespace App\Widgets\Order;

use App\Models\ListingBookedTime;
use Arrilot\Widgets\AbstractWidget;
use Carbon\Carbon;

class BookTimeWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];
	
	public function calculate_price($listing, $params)
    {

        $fee_percentage = setting('marketplace_percentage_fee');
        $fee_transaction = setting('marketplace_transaction_fee');

        $start_date = isset($params['start_date']) ? $params['start_date'] : null;
        $selected_slot = isset($params['slot']) ? $params['slot'] : null;
        $quantity = isset($params['quantity']) ? $params['quantity'] : 1;


        $error = false;
        $user_choice = [];
        $timeslots = [];

        //date, time, qty
        $subtotal = $quantity * $listing->price;
        $service_fee_percentage = $subtotal * ($fee_percentage/100);
        $service_fee_transaction = $fee_transaction;
        $service_fee = $service_fee_percentage + $service_fee_transaction;
        $total = $subtotal + $service_fee;

        $session_length = 15;
        if ($start_date) {
            $start_date = Carbon::createFromFormat('d-m-Y', $start_date);
            $day_of_week = $start_date->format('N');
            if ($listing->timeslots) {
                foreach ($listing->timeslots as $timeslot) {
                    if ($day_of_week == $timeslot['day']) {
                        $start_time = (int)$timeslot['start_time'];
                        for ($i = 0; $i <= 60 - $session_length; $i += $session_length) {
                            $timeslots[] = str_pad($start_time, 2, "0", STR_PAD_LEFT) . ':' . str_pad($i, 2, "0");
                        }
                    }
                }
            }



            //hide taken slots
            $booked_slots = ListingBookedTime::where('listing_id', $listing->id)->where('booked_date', $start_date->toDateString())->get();
            $taken_slots = [];
            foreach ($booked_slots as $slot) {
                if($slot->quantity >= $listing->stock) {
                    $taken_slots[] = $slot->start_time;
                }
            }
            #dd($taken_slots);
            if($taken_slots)
                $timeslots = array_diff( $timeslots, $taken_slots );

            if (!$error && count($timeslots) == 0) {
                $error = __('Sorry, not available time slots. Try another date.');
            }
        }

        if($selected_slot && !in_array($selected_slot, $timeslots)) {
            $error = __('Invalid slot.');
        }

        if($start_date && !$selected_slot) {
            $error = __('Please select a slot.');
        }
		
		//now check if we have any slots left for this time
        if($start_date && $selected_slot) {
            $booked_time = ListingBookedTime::where('listing_id', $listing->id)
                ->where('booked_date', $start_date->toDateString())
                ->where('start_time', $selected_slot)
                ->first();
            if ($booked_time) {
                $remaining = $listing->stock - $booked_time->quantity;
                if ($quantity > $remaining) {
                    $error = __('Sorry no availability. Please try a different day/time.');
                }
            }
        }

		$price_items = [
			[
				'key' 	=> 'price',
				'label' => __(':price x :quantity :unit_label', ['price' => format_money($listing->price, $listing->currency), 'quantity' => $quantity, 'unit_label' => $listing->unit]),
				'price' => $subtotal
			],
			[
				'key'	=> 'service',
				'label' => __('Service fee'),
				'price' => $service_fee
			],
		];

        if($start_date) {
            $user_choice[] = ['group' => 'dates', 'name' => 'Selected day', 'value' => $start_date->toRfc7231String()];
            $user_choice[] = ['group' => 'dates', 'name' => 'Slot', 'value' => $selected_slot];
        }

		return [
            'user_choice'	=>	$user_choice,
            'error'			=>	$error,
			'total'			=>	$total,
			'service_fee'	=>	$service_fee,
			'price_items'	=>	$price_items,
			'timeslots'	=>	$timeslots,
		];
	
	}

    public function decrease_stock($order, $listing)
    {
        //add quantity to the listing_booked_dates table
        $booked_date = Carbon::createFromFormat('d-m-Y', $order->listing_options['start_date']);
        $slot = $order->listing_options['slot'];

        $booked_slot = ListingBookedTime::firstOrCreate([
            'listing_id' => $order->listing->id,
            'booked_date' => $booked_date->toDateString(),
            'start_time' => $slot,
        ], ['quantity' => 0]);

        $booked_slot->increment('quantity', $order->listing_options['quantity']);

    }

    public function validate_payment($listing, $request)
    {
		return $this->calculate_price($listing, $request);
	}
	
    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run($listing)
    {
        //
		$selected_slot = request('slot');
		$start_date = request('start_date');
		$error = null;
		if($start_date) {
			try {
				$start_date = Carbon::createFromFormat('d-m-Y', $start_date);
			} catch (\Exception $e) {
				$start_date = null;
				if(request('start_date'))
					$error = __('Invalid date');
			}
		}

		$quantity = 1;
		$price_items = [];
		$total = 0;
		$timeslots = [];
		#$result = $this->calculate_price($listing);
        #dd(request()->all());
        $result = $this->calculate_price($listing, request()->all());

        if($result) {
			$price_items = $result['price_items'];
			$total = $result['total'];
			$timeslots = $result['timeslots'];
			if($result['error'])
				$error = $result['error'];
		}
		

		
        return view('listing.widgets.book_time_widget', [
            'config' => $this->config,
            'qs' 	        => http_build_query(request()->all()),
            'selected_slot' => $selected_slot,
            'error' => $error,
            'start_date' => $start_date,
            'timeslots' => $timeslots,
            'listing' => $listing,
            'quantity' => $quantity,
            'price_items' => $price_items,
            'total' => $total,
        ]);
    }
}
