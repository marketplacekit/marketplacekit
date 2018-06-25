<?php

namespace App\Listeners;

use App\Events\OrderDeclined;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class IncreaseStock
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
     * @param  OrderDeclined  $event
     * @return void
     */
    public function handle(OrderDeclined $event)
    {
        //
    }
}
