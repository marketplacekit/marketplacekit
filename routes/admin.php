<?php

Route::group(['as' => 'panel.', 'prefix' => 'panel', 'middleware' => ['web', 'role:admin'], 'namespace' => '\Admin'], function()
{
    Route::get('/', 'PanelController@index');
    Route::resource('listings', 'ListingsController');
	Route::get('/listings/{listing}/duplicate', 'ListingsController@duplicate')->name('listings.duplicate');
    Route::resource('categories', 'CategoriesController');
    Route::resource('users', 'UsersController');
	Route::resource('roles', 'RolesController');
    Route::resource('pages', 'PagesController');
    Route::resource('menu', 'MenuController');
    Route::any('/settings/remove', 'SettingsController@remove')->name('settings.remove');
    Route::resource('payments', 'PaymentsController');
    Route::resource('settings', 'SettingsController');
    Route::resource('orders', 'OrdersController');
    Route::resource('home', 'HomeController');
    Route::resource('addons', 'AddonsController');
    Route::get('/addon/{id}/toggle', 'AddonsController@toggle');
    Route::resource('themes', 'ThemesController');
    Route::get('/theme/{id}/toggle', 'ThemesController@toggle');
    Route::resource('pricing-models', 'PricingModelsController');
    Route::resource('fields', 'FieldsController');

});

Route::group(['as' => 'panel.', 'prefix' => 'panel', 'middleware' => ['web', 'role:admin|moderator'], 'namespace' => '\Admin'], function()
{
    Route::get('/', 'PanelController@index');
    Route::resource('users', 'UsersController');
});