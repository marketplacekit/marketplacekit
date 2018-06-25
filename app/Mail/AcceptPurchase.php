<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AcceptPurchase extends Mailable
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
        $view = "emails.$locale.accept_purchase";
        if(!view()->exists($view)) {
            $view = "emails.en.accept_purchase";
        }
        return $this->markdown($view)
                    ->with([
                        'name' => $this->order->user->display_name,
                        'title' => $this->order->listing->title,
                        'url' => route('account.purchase-history.index'),
                    ]);
    }
}
