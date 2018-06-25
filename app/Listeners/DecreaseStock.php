<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DecreaseStock
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrderPlaced  $event
     * @return void
     */
    public function handle(OrderPlaced $event)
    {
        //
        $listing = $event->order->listing;

        #call widget and do processing there
        $widget = '\App\Widgets\Order\\'.camel_case($listing->pricing_model->widget).'Widget';
        $widget = new $widget();
        $widget->decrease_stock($event->order, $listing);


    }
}
