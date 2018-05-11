<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Mail\ResetPassword as Mailable;

class ResetPassword extends Notification
{
    use Queueable;

    public $token;
    public $notifiable;
    /**
     * Create a notification instance.
     *
     * @param string $token
     * @return void
     */
    public function __construct($token, $notifiable)
    {
        $this->token = $token;
        $this->notifiable = $notifiable;

    }
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $locale = $notifiable->locale?:'en';
        return (new MailMessage)->markdown("emails.$locale.reset_password", [
            'url' => url(config('app.url').route('password.reset', $this->token, false)),
            'name' => $this->notifiable->name,
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
