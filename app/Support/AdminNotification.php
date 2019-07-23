<?php

namespace App\Support;

use App\Models\User;
use Notification;

class AdminNotification
{

    public static function notify($subject, $message, $url = null)
    {
        Notification::send(User::find(1), new \App\Notifications\AdminNotification($subject, $message, $url));
    }

}
