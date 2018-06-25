<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use LaravelLocalization;
class ContactUs extends Mailable
{
    use Queueable, SerializesModels;
    protected $params;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        //
        $this->params = $params;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $locale = LaravelLocalization::getCurrentLocale();
        $view = "emails.$locale.contact_us";
        if(!view()->exists($view)) {
            $view = "emails.en.contact_us";
        }

        return $this->markdown($view)
            ->replyTo($this->params['email_address'])
            ->subject(__("You've received a new contact us message"))
            ->with([
                'name' => $this->params['name'],
                'email_address' => $this->params['email_address'],
                'comment' => $this->params['comment']
            ]);
    }
}
