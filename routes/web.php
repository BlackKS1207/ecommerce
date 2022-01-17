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

Route::get('/', function () {
    return view('welcome');
});

/* Products Route */

Route::get('/boutique','ProductController@index')->name('products.index');
Route::get('/boutique/{slug}','ProductController@show')->name('products.show');
Route::get('/search', 'ProductController@search')->name('products.search');

/* Card Route */
Route::group(['middleware' => ['auth']], function () {
    Route::get('/panier', 'CardController@index')->name('cart.index');
    Route::post('/panier/ajouter', 'CardController@store')->name('cart.store');
    Route::patch('/panier/{rowId}','CardController@update')->name('cart.update');
    Route::delete('panier/{rowId}','CardController@destroy')->name('cart.destroy');
    Route::post('/coupon', 'CardController@storeCoupon')->name('cart.store.coupon');
    Route::delete('/coupon', 'CardController@destroyCoupon')->name('cart.destroy.coupon');
});


/* Checkout Routes */
Route::group(['middleware' => ['auth']], function () {
    Route::get('/paiement', 'CheckoutController@index')->name('checkout.index');
    Route::post('/paiement','CheckoutController@store')->name('checkout.store');
    Route::get('/merci','CheckoutController@thankYou')->name('checkout.thankyou');
});

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
