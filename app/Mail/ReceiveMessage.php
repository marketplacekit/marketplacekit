<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReceiveMessage extends Mailable
{
    use Queueable, SerializesModels;
    protected $user;
    protected $sender;
    protected $conversationId;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $sender, $conversationId)
    {
        //
        $this->user = $user;
        $this->sender = $sender;
        $this->conversationId = $conversationId;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $locale = $this->user->locale?:'en';
        return $this->markdown("emails.$locale.receive_message")
                    ->with([
                        'name' => $this->user->display_name,
                        'sender' => $this->sender->display_name,
                        'url' => route('inbox.show', $this->conversationId),
                    ]);
    }
}
