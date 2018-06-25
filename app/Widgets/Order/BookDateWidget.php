<?php

namespace App\Widgets\Order;

use App\Models\ListingBookedDate;
use Arrilot\Widgets\AbstractWidget;
use Carbon\Carbon;


class BookDateWidget extends AbstractWidget
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

        $start_date = isset($params['start_date'])?$params['start_date']:null;
        $end_date = isset($params['end_date'])?$params['end_date']:null;
        $quantity = isset($params['quantity'])?$params['quantity']:1;

        $error = null;
        $user_choice = [];

        $start_date = Carbon::parse($start_date);
        $end_date = Carbon::parse($end_date);

        $units = $end_date->diffInDays($start_date);
        if($listing->pricing_model->duration_name == 'day' && $start_date) {
            $units++;
        }

        $subtotal = $units * $listing->price * $quantity;
        $service_fee_percentage = $subtotal * ($fee_percentage/100);
        $service_fee_transaction = $fee_transaction;
        $service_fee = $service_fee_percentage + $service_fee_transaction;
        $total = $subtotal + $service_fee;

        $price_items = [
            [
                'label' => __(':price x :units :unit_label', ['price' => format_money($listing->price, $listing->currency), 'units' => $units, 'unit_label' => _p($listing->pricing_model->duration_name, $units)]), 'price' => format_money($subtotal, $listing->currency)
            ],
            ['label' => __('Service fee'), 'price' => format_money($service_fee, $listing->currency)],
        ];

        if($units < $listing->min_duration) {
            $error = __('Please select at least :days day(s)/night(s)', ['days' => $listing->min_duration]);
        }

        #check if we have enough "stock" rooms for the day
        for($booked_date = $start_date; $booked_date->lte($end_date); $start_date->addDay()) {

            $booked_day = ListingBookedDate::where('listing_id', $listing->id)->where('booked_date', $booked_date->toDateString())->first();
            if ($booked_day) {
                $remaining = $listing->stock - $booked_day->quantity;
                if ($quantity > $remaining) {
                    $error = __('Sorry no availability. Please try a different date range.');
                    break;
                }
            }
        }

        if($start_date && $end_date) {
            $user_choice[] = ['group' => 'dates', 'name' => 'Start day', 'value' => $start_date->toRfc7231String()];
            $user_choice[] = ['group' => 'dates', 'name' => 'End day', 'value' => $end_date->toRfc7231String()];
        }

        return [
            'user_choice'	=>	$user_choice,
            'error'			=>	$error,
            'total'			=>	$total,
            'units'	        =>	$units,
            'service_fee'	=>	$service_fee,
            'price_items'	=>	$price_items,
        ];

    }

    public function decrease_stock($order, $listing)
    {
        //add quantity to the listing_booked_dates table
        $start_date = Carbon::createFromFormat('d-m-Y', $order->listing_options['start_date']);
        $end_date = Carbon::createFromFormat('d-m-Y', $order->listing_options['end_date']);

        if($listing->unit == 'night') {
            $end_date = $end_date->subDay();
        }

        for($booked_date = $start_date; $booked_date->lte($end_date); $start_date->addDay()) {

            $booked_day = ListingBookedDate::firstOrCreate([
                'listing_id' => $order->listing->id,
                'booked_date' => $booked_date->toDateString(),
            ], ['quantity' => 0]);

            $booked_day->increment('quantity', $order->listing_options['quantity']);
        }

    }

    public function validate_payment($listing, $request)
    {
        $result = $this->calculate_price($listing, $request);
        return $result;
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run($listing)
    {
        //
        $result = $this->calculate_price($listing, request()->all());

        //we also need to figure out what dates are fully taken - in the next 3 months?
        $start = Carbon::now()->startOfDay();
        $end = Carbon::now()->addMonths(3)->endOfDay();
        $booked_dates = ListingBookedDate::where('listing_id', $listing->id)
                                    ->whereBetween('booked_date', [$start, $end])
                                    ->where('quantity', '>=', $listing->stock)
                                    ->get()
                                    ->pluck('booked_date_string');

        return view('listing.widgets.book_date_widget', [
            'config' => $this->config,
            'qs' 	        => http_build_query(request()->all()),
            'start_date' => request('start_date'),
            'end_date' => request('end_date'),
            'booked_dates' => $booked_dates,
            'listing' => $listing,
            'units' => $result['units'],
            'service_fee' 	=> $result['service_fee'],
            'price_items' 	=> $result['price_items'],
            'total' 	=> $result['total'],
            'error' 	=> $result['error'],
        ]);
    }
}
