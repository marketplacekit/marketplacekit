<?php

namespace App\Listeners;

use Jrean\UserVerification\Events\UserVerified;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;

class EmailVerified
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
     * @param  UserVerified  $event
     * @return void
     */
    public function handle(UserVerified $event)
    {
        //
		$user = User::find($event->user->id);
		$user->verification_token = null;
        $user->verified = true;
		$user->save();
    }
}
