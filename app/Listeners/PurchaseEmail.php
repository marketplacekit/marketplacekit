<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Mail\PurchaseItem;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class PurchaseEmail
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
        Mail::to($event->order->listing->user->email)->send(new PurchaseItem($event->order));
    }
}
