<?php

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/* Config */
Route::get('configs', 'BookingController@getConfigs')->name('api.get_configs');
/* Service */
Route::get('{type}/search', 'SearchController@search')->name('api.search2');
Route::get('{type}/detail/{id}', 'SearchController@detail')->name('api.detail');
Route::get('{type}/availability/{id}', 'SearchController@checkAvailability')->name('api.service.check_availability');

Route::get('{type}/filters', 'SearchController@getFilters')->name('api.service.filter');

Route::group(['middleware' => 'api'], function () {
    Route::post('{type}/write-review/{id}', 'ReviewController@writeReview')->name('api.service.write_review');
});

/* Layout HomePage */
Route::get('home-page', 'BookingController@getHomeLayout')->name('api.get_home_layout');

/* Register - Login */
Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('me', 'AuthController@me');
    Route::post('me', 'AuthController@updateUser');
    Route::post('change-password', 'AuthController@changePassword');
    Route::post('forget-password', 'AuthController@forgetPassword');
});

/* User */
Route::group(['prefix' => 'user', 'middleware' => ['api'],], function ($router) {
    Route::get('booking-history', 'UserController@getBookingHistory')->name("api.user.booking_history");
    Route::post('/wishlist', 'UserController@handleWishList')->name("api.user.wishList.handle");
    Route::delete('/wishlist', 'UserController@handleWishList')->name("api.user.wishList.handle");
    Route::get('/wishlist', 'UserController@indexWishlist')->name("api.user.wishList.index");
});

/* Location */
Route::get('locations', 'LocationController@search')->name('api.location.search');
Route::get('locations/{id}', 'LocationController@detail')->name('api.location.detail');

// Booking
Route::group(['prefix' => config('booking.booking_route_prefix')], function () {
    Route::post('/addToCart', 'BookingController@addToCart')->middleware('api')->name("api.booking.add_to_cart");
    Route::post('/addEnquiry', 'BookingController@addEnquiry')->name("api.booking.add_enquiry");
    Route::post('/doCheckout', 'BookingController@doCheckout')->name('api.booking.doCheckout');
    Route::get('/confirm/{gateway}', 'BookingController@confirmPayment');
    Route::get('/cancel/{gateway}', 'BookingController@cancelPayment');
    Route::get('/{code}', 'BookingController@detail');
    Route::get('/{code}/thankyou', 'BookingController@thankyou')->name('booking.thankyou');
    Route::get('/{code}/checkout', 'BookingController@checkout');
    Route::get('/{code}/check-status', 'BookingController@checkStatusCheckout');
    Route::post('/create/{object_model}', 'BookingController@create');
});

// Gateways
Route::get('/gateways', 'BookingController@getGatewaysForApi');

// News
Route::get('news', 'NewsController@search')->name('api.news.search');
Route::get('news/category', 'NewsController@category')->name('api.news.category');
Route::get('news/{id}', 'NewsController@detail')->name('api.news.detail');

// Hotels
Route::get('hotels', 'HotelController@index')->name('api.hotels.index');
Route::get('hotels/{id}', 'HotelController@detail')->name('api.hotels.detail');
Route::post('hotels/availability', 'HotelController@checkAvailability')->name('api.hotels.availability');

// Flight
//Route::get('flights', 'FlightController@index')->name('api.flights.index');
//Route::get('flights/{id}', 'FlightController@detail')->name('api.flights.detail');

// Tour
Route::get('tours', 'TourController@index')->name('api.tours.index');
Route::get('tours/availability', 'TourController@checkAvailability')->name('api.tours.availability');
Route::get('tours/{id}', 'TourController@detail')->name('api.tours.detail');

// Event
Route::get('events', 'EventController@index')->name('api.events.index');
Route::get('events/{id}', 'EventController@detail')->name('api.events.detail');

// Notification
Route::get('notifications', 'NotificationController@index')->name('api.notification.index');
Route::get('notifications/all', 'NotificationController@allNotification')->name('api.notification.all');

// Car
Route::get('cars', 'CarController@index')->name('api.cars.index');
Route::get('cars/{slug}', 'CarController@detail')->name('api.cars.detail');

// Car
Route::get('spaces', 'SpaceController@index')->name('api.spaces.index');
Route::get('spaces/{slug}', 'SpaceController@detail')->name('api.spaces.detail');


/* Media */
Route::group(['prefix' => 'media', 'middleware' => 'auth:api'], function () {
    Route::post('/store', 'MediaController@store')->name("api.media.store");
});

Route::fallback(function () {
    return response([
        'message' => "404 NOT FOUND"
    ], 404);
});
