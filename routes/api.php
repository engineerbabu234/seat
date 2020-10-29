<?php
use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middleware' => ['localization']], function () {
	//============AuthController==========================
	Route::post('/sign_up', 'api\AuthController@signUp');
	Route::post('/update_details_1', 'api\AuthController@update_details1');

	Route::post('/login', 'api\AuthController@login');
	Route::post('/forgot_password', 'api\AuthController@forgotPassword');
	Route::post('/forgot_password_change', 'api\AuthController@forgotPasswordChange');
	Route::post('/profile_update', 'api\AuthController@profileUpdate');
	Route::post('/notification_update_status', 'api\AuthController@notificationUpdateStatus');

	Route::get('/get_profile', 'api\AuthController@getProfile');
	Route::get('/get_notfication', 'api\AuthController@getNotfication');
	Route::post('/update_details_1', 'api\AuthController@updateDetails1');
	Route::post('/update_details_2', 'api\AuthController@updateDetails2');
	Route::get('/get_vehicle_type', 'api\AuthController@getVehicleType');
	Route::post('/change_password', 'api\AuthController@changePassword');
	Route::post('/update_address', 'api\AuthController@updateAddress');
	Route::get('/get_lat_lng', 'api\AuthController@getLatLng');
	Route::get('/page', 'api\AuthController@page');
	
	
	//============UserController==========================
	Route::post('/user/post_trip', 'api\UserController@postTrip');
	Route::post('/user/trip_status_change', 'api\UserController@tripStatusChange');
	Route::get('/user/get_trips', 'api\UserController@getTrips');
	Route::get('/user/trip_detail', 'api\UserController@tripDetail');
	Route::post('/user/driver_rating', 'api\UserController@driverRating');
	Route::post('/user/get_driving_distance', 'api\UserController@GetDrivingDistance');
	Route::get('/user/get_nearest_driver', 'api\UserController@getNearestDriver');
	Route::get('/user/trip_payment', 'api\UserController@tripPayment');
	Route::post('/user/card_payment', 'api\UserController@cardPayment');


	//============DriverController==========================
	Route::get('/driver/get_trips', 'api\DriverController@getTrips');
	Route::get('/driver/trip_detail', 'api\DriverController@tripDetail');
	Route::get('/driver/get_requests', 'api\DriverController@getRequests');
	Route::post('/driver/trip_status_change', 'api\DriverController@tripStatusChange');
	Route::post('/driver/trip_cancel', 'api\DriverController@tripCancel');
	//============ChatController==========================
	Route::post('/send_message', 'api\ChatController@sendMessage');
	
	Route::get('/get_chat', 'api\ChatController@getChat');
});
