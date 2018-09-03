<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],        
		'App\Events\OrderPlaced' => [
            'App\Listeners\PurchaseEmail',
            'App\Listeners\DecreaseStock',
        ],		
		'App\Events\OrderDeclined' => [
            'App\Listeners\IncreaseStock',
        ],
		\Jrean\UserVerification\Events\UserVerified::class => [
			'App\Listeners\EmailVerified',
        ],
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\LogSuccessfulLogin',
        ],
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            'SocialiteProviders\PayPal\PayPalExtendSocialite@handle',
            'SocialiteProviders\PayPalSandbox\PayPalSandboxExtendSocialite@handle',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
