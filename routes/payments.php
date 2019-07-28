<?php

Route::group(['as' => 'payments.', 'prefix' => 'payments', 'middleware' => ['web', 'auth'], 'namespace' => '\Payments'], function()
{
/*
    Route::get('offline/connect/{key}', 'OfflineController@connect')->name('offline.connect');
    Route::get('offline/{checkout_session}', 'OfflineController@index')->name('offline.index');
    Route::post('offline/{checkout_session}', 'OfflineController@store')->name('offline.store');

    Route::get('paypal-split/connect', 'PaypalSplitController@connect')->name('paypal-split.connect');
    Route::any('paypal-split/connected', 'PaypalSplitController@connected')->name('paypal-split.connected');
    Route::any('paypal-split/cancel', 'PaypalSplitController@cancel')->name('paypal-split.cancel');
    Route::any('paypal-split/callback', 'PaypalSplitController@callback')->name('paypal-split.callback');
    Route::get('paypal-split/{checkout_session}', 'PaypalSplitController@index')->name('paypal-split.index');
    #Route::get('checkout/{listing}', 'CheckoutController@index')->name('checkout');


    #Route::get('stripe/{checkout_session}', 'StripeController@index')->name('stripe.index');
    #Route::post('stripe/{checkout_session}', 'StripeController@store')->name('stripe.store');

    Route::get('external/connect/{key}', 'ExternalController@connect')->name('external.connect');
    Route::get('external/connected/{key}', 'ExternalController@connected')->name('external.connected');
    Route::get('external/{checkout_session}/{key}', 'ExternalController@index')->name('external.index');
    Route::get('external/{checkout_session}/{key}/callback', 'ExternalController@callback')->name('external.callback');
    Route::post('external/{checkout_session}/{key}', 'ExternalController@store')->name('external.store');


	Route::get('paypal-express/connect', 'PaypalExpressController@connect')->name('paypal-express.connect');
    Route::any('paypal-express/connected', 'PaypalExpressController@connected')->name('paypal-express.connected');
    Route::any('paypal-express/cancel', 'PaypalExpressController@cancel')->name('paypal-express.cancel');
    Route::any('paypal-express/callback', 'PaypalExpressController@callback')->name('paypal-express.callback');
    Route::get('paypal-express/{checkout_session}', 'PaypalExpressController@index')->name('paypal-express.index');
*/
});
