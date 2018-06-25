<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeclinePurchase extends Mailable
{
    use Queueable, SerializesModels;
    protected $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        //
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $locale = $this->order->user->locale?:'en';
        return $this->markdown("emails.$locale.decline_purchase")
            ->with([
                'name' => $this->order->user->display_name,
                'title' => $this->order->listing->title,
                'url' => route('account.purchase-history.index'),
            ]);
    }
}
