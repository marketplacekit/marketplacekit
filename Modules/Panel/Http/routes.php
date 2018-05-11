<?php

Route::group(['as' => 'panel.', 'prefix' => 'panel', 'middleware' => ['web', 'auth.admin'], 'namespace' => 'Modules\Panel\Http\Controllers'], function()
{
    Route::get('/', 'PanelController@index');
    Route::resource('listings', 'ListingsController');
    Route::resource('categories', 'CategoriesController');
    Route::resource('users', 'UsersController');
    Route::resource('pages', 'PagesController');
    Route::resource('menu', 'MenuController');
    Route::resource('settings', 'SettingsController');
    Route::resource('orders', 'OrdersController');
    Route::resource('home', 'HomeController');
    Route::resource('pricing-models', 'PricingModelsController');
    Route::resource('fields', 'FieldsController');

});
