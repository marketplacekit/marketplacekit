<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use LaravelLocalization;
use Location;
use GeoIP;

class LogSuccessfulLogin
{
    public $request;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        //
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        //
        $user = $event->user;
        $user->last_login_at = date('Y-m-d H:i:s');
        $user->last_login_ip = $this->request->ip();
        $user->locale =  LaravelLocalization::getCurrentLocale();

        //let's get the country
        $user->region = GeoIP::getRegion();
        $user->city = GeoIP::getCity();
        $user->country = GeoIP::getCountryCode();
        $user->country_name = GeoIP::getCountry();

        $user->save();
    }
}
