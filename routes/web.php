<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return view('welcome');
});
*/


Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => 'jailBanned'], function()
{
	Auth::routes();
    Route::get('email-verification', 'Auth\EmailVerificationController@sendEmailVerification')->name('email-verification.send');
    Route::get('email-verification/error', 'Auth\EmailVerificationController@getVerificationError')->name('email-verification.error');
    Route::get('email-verification/check/{token}', 'Auth\EmailVerificationController@getVerification')->name('email-verification.check');

    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/browse', 'BrowseController@listings')->name('browse');

    Route::get('/pages/{slug}', 'PageController@index')->name('page');
	Route::get('/contact', 'ContactController@index')->name('contact');
	Route::post('/contact', 'ContactController@postIndex')->name('contact.post');
	Route::get('/sitemap', 'SitemapController@index')->name('sitemap');

	Route::get('/profile/{user}', 'ProfileController@index')->name('profile'); //PROFILE
	Route::get('/profile/{user}/reviews', 'ProfileController@reviews')->name('profile.reviews');

	//LISTINGS
	Route::group(['prefix' => 'listing'], function()
	{
		Route::get('/{listing}/{slug}', 'ListingController@index')->name('listing');
		Route::get('/{listing}/{slug}/spotlight', 'ListingController@spotlight')->middleware('auth.ajax')->name('listing.spotlight');
		Route::get('/{listing}/{slug}/verify', 'ListingController@verify')->middleware('auth.ajax')->name('listing.verify');
		Route::get('/{listing}/{slug}/star', 'ListingController@star')->middleware('auth.ajax')->name('listing.star');
		Route::get('/{listing}/{slug}/edit', 'ListingController@edit');
		Route::any('/{id}/update', 'ListingController@update')->name('listing.update');

		Route::resource('/{listing}/{slug}/reviews', 'ReviewsController');
		#Route::get('/{listing}/{slug}/times', 'ListingController@getTimes');
		#Route::post('/{listing}/{slug}/times', 'ListingController@postTimes');
	});


	//ACCOUNT
	Route::group(['middleware' => 'auth', 'prefix' => 'account', 'as' => 'account.', 'namespace' => 'Account'], function()
	{
		Route::get('/', function () {
			return redirect(route('account.edit_profile.index'));
		});
		Route::resource('change_password', 'PasswordController');
		Route::resource('edit_profile', 'ProfileController');
		Route::resource('favorites', 'FavoritesController');
		Route::resource('listings', 'ListingsController');
	});

	//REQUIRES AUTHENTICATION
	Route::group(['middleware' => 'auth'], function () {

		//INBOX
		Route::resource('inbox', 'InboxController')->middleware('talk'); //Inbox

		//CREATE LISTING
		Route::resource('create', 'CreateController');
        Route::any('/create/{listing}/session', 'CreateController@session')->name('create.session');
        Route::get('/create/{listing}/images', 'CreateController@images')->name('create.images');
        Route::get('/create/{listing}/additional', 'CreateController@additional')->name('create.additional');
        Route::get('/create/{listing}/pricing', 'CreateController@pricing')->name('create.pricing');
        Route::get('/create/{listing}/times', 'CreateController@getTimes')->name('create.times');
        Route::post('/create/{listing}/times', 'CreateController@postTimes')->name('create.times');

        Route::post('/create/{listing}/uploads', 'CreateController@upload')->name('create.upload');
        Route::delete('/create/{listing}/image/{uuid?}', 'CreateController@deleteUpload')->name('create.delete-image');

        #Route::delete('/uploads/delete/{id}', array('as' => 'upload', 'uses' => 'CreateController@deleteUpload'))->name('create.delete-image');;
		#Route::get('/listings/{id}/session', array('as' => 'create', 'uses' => 'CreateController@session'));

		//CHECKOUT
		Route::get('/checkout/{listing}', 'CheckoutController@index')->name('checkout');
		Route::any('/checkout/process/{listing}', 'CheckoutController@process')->name('checkout.process');
		#Route::any('/checkout/test', 'CheckoutController@test')->name('checkout.test');
		Route::resource('stripe', 'StripeController');
		Route::any('/stripe/connect', 'StripeController@connect')->name('stripe.connect');

        Route::any('/paypal/{listing}/start', 'PaypalController@start')->name('paypal.start');
        Route::any('/paypal/cancel', 'PaypalController@cancel')->name('paypal.cancel');
        Route::any('/paypal/callback', 'PaypalController@callback')->name('paypal.callback');
        Route::any('/paypal/confirm', 'PaypalController@confirm')->name('paypal.confirm');
        #Route::any('/paypal/create_agreement', 'PaypalController@create_agreement')->name('paypal.create_agreement');


    });
	Route::get('login/facebook', 'Auth\LoginController@redirectToProvider');
	Route::get('login/facebook/callback', 'Auth\LoginController@handleProviderCallback');


});

#errors
Route::get('/suspended',function(){
    return 'Sorry something went wrong.';
})->name('error.suspended');

