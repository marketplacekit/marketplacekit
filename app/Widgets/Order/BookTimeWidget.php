<?php

namespace App\Widgets\Order;

use App\Models\ListingBookedTime;
use Arrilot\Widgets\AbstractWidget;
use Carbon\Carbon;
use Applicazza\Appointed\BusinessDay;
use Applicazza\Appointed\Period;
use Applicazza\Appointed\Appointment;
use Applicazza\Appointed;
use VM\TimeOverlapCalculator\Entity\TimeSlot;
use VM\TimeOverlapCalculator\Generator\TimeSlotGenerator;
use VM\TimeOverlapCalculator\TimeOverlapCalculator;

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
		$additional_options = isset($params['additional_option'])?$params['additional_option']:[];
#dev_dd($start_date);

        $error = false;
        $selected_services = false;
        $user_choice = [];
        $timeslots = [];

        $total_price = $listing->price;
        $session_length = (int) $listing->session_duration;
        if($listing->services && isset($params['service'])) {
            $params['service'] = array_keys($params['service']);
            $selected_services = $listing->services->whereIn('id', $params['service']);
            $total_price = $selected_services->sum('price');
            $session_length = $selected_services->sum('duration');
        }

		#additional pricing
        $additional_options_price = $listing->additional_options->reduce(function ($carry, $item) use($additional_options) {
            if(in_array($item->id, array_keys($additional_options)))
                return $carry + $item->price;
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
        $subtotal = ($quantity * $total_price) + $additional_options_price;
        $service_fee_percentage = $subtotal * ($fee_percentage/100);
        if($subtotal == 0)
            $fee_transaction = 0;
        $service_fee_transaction = $fee_transaction;
        $service_fee = $service_fee_percentage + $service_fee_transaction;
        $total = $subtotal + $service_fee;

        $timeSlotGenerator = new TimeSlotGenerator();
        $calculator = new TimeOverlapCalculator();
        $time_slots = [];
        if ($start_date) {
            $start_date = Carbon::createFromFormat('d-m-Y', $start_date);

            $day_of_week = $start_date->format('N');
#dev_dd($day_of_week);
            if ($listing->timeslots) {
                foreach ($listing->timeslots as $timeslot) {
                    if ($day_of_week == $timeslot['day']) {

                        $start_time = (int) $timeslot['start_time'];
                        $end_time = (int) $timeslot['end_time'];

                        $time_slots[] = new TimeSlot(\Applicazza\Appointed\today( $start_time, 0), \Applicazza\Appointed\today($end_time, 0));
                    }
                }
            }
        }

        $free_time_slots = $calculator->mergeOverlappedTimeSlots(
            $timeSlotGenerator,
            $time_slots
        );

        $business_day = new BusinessDay;
        foreach ($free_time_slots as $timeslot) {
            $period = Period::make($timeslot->getStart(), $timeslot->getEnd());
            $business_day->addOperatingPeriods(
                $period
            );
        }

        //add booked appointments
        if($start_date) {
            $booked_slots = ListingBookedTime::where('listing_id', $listing->id)->where('booked_date', $start_date->toDateString())->get();
            foreach ($booked_slots as $booked_slot) {
                if ($booked_slot->quantity >= $listing->stock) {
                    $appointment = Appointment::make(Carbon::parse($booked_slot->start_time), Carbon::parse($booked_slot->start_time)->addMinutes($booked_slot->duration));
                    $business_day->addAppointments($appointment);
                }
            }
        }

        //now generate some time slots to choose from
        $slot_interval = 15;
		if($listing->session_duration)
			$slot_interval = $listing->session_duration;
		#dev_dd($listing->session_duration);
        $available_slots = [];
        $agenda = $business_day->getAgenda();

        foreach($agenda as $timeslot) {
            if($timeslot->jsonSerialize()['status'] == "available") {
                $period = new \League\Period\Period($timeslot->getStartsAt(), $timeslot->getEndsAt());
                foreach ($period->split($slot_interval . ' minutes') as $period) {
                    $available_slots[] = $period->getStartDate()->format('H:i');
                }
            }
        }
        $timeslots = $available_slots;
        if (!$error && count($available_slots) == 0 && $start_date) {
            $error = __('Sorry, no available time slots. Try another date.');
        }

        if($start_date && $selected_slot && !in_array($selected_slot, $available_slots)) {
            $error = __('Invalid slot.');
        }
/*
        dd(($available_slots));


        $agenda = $business_day->getAgenda();
dd($agenda);

        $periods = [];
        if ($start_date) {
            $start_date = Carbon::createFromFormat('d-m-Y', $start_date);

            $day_of_week = $start_date->format('N');

            if ($listing->timeslots) {
                foreach ($listing->timeslots as $timeslot) {
                    if ($day_of_week == $timeslot['day']) {

                        $start_time = (int) $timeslot['start_time'];
                        $end_time = (int) $timeslot['end_time'];

                        $period = Period::make(\Applicazza\Appointed\today( $start_time, 0), \Applicazza\Appointed\today($end_time, 0));
                        $business_day->addOperatingPeriods(
                            $period
                        );
                    }
                }
            }
        }



        //now generate slots
        $appointment = Appointment::make(\Applicazza\Appointed\today( 9, 00), \Applicazza\Appointed\today(10, 30));
        $business_day->addAppointments(
            $appointment
        );
        $agenda = $business_day->getAgenda();
        dd($agenda);
*/

        #$session_length = 15;
        /*if ($start_date) {
            $start_date = Carbon::createFromFormat('d-m-Y', $start_date);
            $day_of_week = $start_date->format('N');
            if ($listing->timeslots) {
                foreach ($listing->timeslots as $timeslot) {
                    if ($day_of_week == $timeslot['day']) {
                        $start_time = (int) $timeslot['start_time'];
                        $max_session_length =($session_length <= 60)?$session_length:60;
                        for ($i = 0; $i <= 60 - $max_session_length; $i += $max_session_length) {
                            $timeslots[] = str_pad($start_time, 2, "0", STR_PAD_LEFT) . ':' . str_pad($i, 2, "0");
                        }
                    }
                }
            }

            //hide taken slots
            $booked_slots = ListingBookedTime::where('listing_id', $listing->id)->where('booked_date', $start_date->toDateString())->get();
            $taken_slots = [];
            #dd($booked_slots);

            foreach ($booked_slots as $slot) {
                if($slot->quantity >= $listing->stock) {
                    $taken_slots[] = [$slot->start_time, Carbon::parse($slot->start_time)->addMinutes($session_length)->format('H:i')];
                }
            }
            dd($taken_slots);
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
            //$error = __('Please select a slot.');
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
        }*/

		$price_items = [
			[
				'key' 	=> 'price',
				'label' => __('Subtotal'),
				'price' => $subtotal
			],
			[
				'key'	=> 'service',
				'label' => __('Service fee'),
				'price' => $service_fee,
				'notice' => __('This fee helps cover the costs of operating the website'),
			],
		];
#dd($selected_services);
        if($start_date) {
            $user_choice[] = ['group' => 'dates', 'name' => 'Selected day', 'value' => $start_date->toFormattedDateString()];
            $user_choice[] = ['group' => 'dates', 'name' => 'Slot', 'value' => $selected_slot];
            if(isset($selected_services) && $selected_services) {
                foreach($selected_services as $selected_service) {
                    $user_choice[] = ['group' => 'services', 'name' => 'Service', 'value' => $selected_service['name']];
                }
            }
        }
#dev_dd($timeslots);
		return [
            'user_choice'	=>	$user_choice,
            'error'			=>	$error,
			'total'			=>	$total,
			'duration'		=>	$session_length,
			'service_fee'	=>	$service_fee,
			'price_items'	=>	$price_items,
			'timeslots'	    =>	$timeslots,
		];
	
	}

    public function decrease_stock($order, $listing)
    {
        //add quantity to the listing_booked_dates table
        $booked_date = Carbon::createFromFormat('d-m-Y', $order->listing_options['start_date']);
        $slot = $order->listing_options['slot'];
        $duration = $order->listing_options['duration'];

        $booked_slot = ListingBookedTime::firstOrCreate([
            'listing_id' => $order->listing->id,
            'booked_date' => $booked_date->toDateString(),
            'start_time' => $slot,
            'duration' => $duration,
        ], ['quantity' => 0]);

        $booked_slot->increment('quantity', $order->listing_options['quantity']);

    }

    public function validate_payment($listing, $request)
    {
		return $this->calculate_price($listing, $request);
	}
	
    private function getDisabledDates($listing) {
	
		#find availability for next 3 months
		$start = Carbon::now()->startOfDay();
        $end = Carbon::now()->addMonths(3)->endOfDay();
		$weekdays = [];
		if($listing->timeslots && is_array($listing->timeslots)) {
			$weekdays = array_values(collect($listing->timeslots)->pluck('day')->unique()->toArray());
		}
		$disabled_dates = [];
		for($date = $start; $date->lte($end); $date->addDay()) {
		    $day_of_week = $date->format('N');
			if (!in_array($day_of_week, $weekdays)) {
				$disabled_dates[] = $date->format('d-m-Y');
			}
		}
		
		return ['weekdays' => $weekdays, 'disabled_dates' => $disabled_dates];
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

        $duration = 0;
		#$result = $this->calculate_price($listing);
        #dd(request()->all());
        $result = $this->calculate_price($listing, request()->all());

        if($result) {
			$price_items = $result['price_items'];
			$total = $result['total'];
			$timeslots = $result['timeslots'];
            $duration = $result['duration'];
			if($result['error'])
				$error = $result['error'];
		}
		
		$disabled_dates = $this->getDisabledDates($listing);
        #dd($disabled_dates);
        return view('listing.widgets.book_time_widget', [
            'config' => $this->config,
            'qs' 	        => http_build_query(request()->all()),
            'selected_slot' => $selected_slot,
            'duration' => $duration,
            'error' => $error,
            'start_date' => $start_date,
            'timeslots' => $timeslots,
			'weekdays' => $disabled_dates['weekdays'],
			'booked_dates' => $disabled_dates['disabled_dates'],
            'listing' => $listing,
            'quantity' => $quantity,
            'price_items' => $price_items,
            'total' => $total,
        ]);
    }
}
