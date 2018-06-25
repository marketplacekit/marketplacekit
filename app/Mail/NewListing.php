<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewListing extends Mailable
{
    use Queueable, SerializesModels;

    protected $listing;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($listing)
    {
        //
        $this->listing = $listing;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $locale = 'en';
        return $this->markdown("emails.$locale.new_listing")
            ->with([
                'title' => $this->listing->title,
                'url' => route('listing', [$this->listing, $this->listing->slug]),
            ]);
    }
}
