<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use LaravelLocalization;

class PurchaseItem extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

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
        $locale = LaravelLocalization::getCurrentLocale();
        $view = "emails.$locale.purchase_item";
        if(!view()->exists($view)) {
            $view = "emails.en.purchase_item";
        }
        return $this->markdown($view)
            ->subject(__("New order/request"))
            ->with([
                'name' => $this->order->listing->user->display_name,
                'title' => $this->order->listing->title,
                'url' => route('account.orders.show', [$this->order]),
            ]);
    }
}
