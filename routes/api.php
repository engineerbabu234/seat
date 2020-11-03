<?php
use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['localization']], function () {
    //============AuthController==========================
    Route::post('/sign_up', 'AuthController@signUp');
    Route::post('/update_details_1', 'AuthController@update_details1');

    Route::post('/login', 'AuthController@login');
    Route::post('/forgot_password', 'AuthController@forgotPassword');
    Route::post('/forgot_password_change', 'AuthController@forgotPasswordChange');
    Route::post('/profile_update', 'AuthController@profileUpdate');
    Route::post('/notification_update_status', 'AuthController@notificationUpdateStatus');

    Route::get('/get_profile', 'AuthController@getProfile');
    Route::get('/get_notfication', 'AuthController@getNotfication');
    Route::post('/update_details_1', 'AuthController@updateDetails1');
    Route::post('/update_details_2', 'AuthController@updateDetails2');
    Route::get('/get_vehicle_type', 'AuthController@getVehicleType');
    Route::post('/change_password', 'AuthController@changePassword');
    Route::post('/update_address', 'AuthController@updateAddress');
    Route::get('/get_lat_lng', 'AuthController@getLatLng');
    Route::get('/page', 'AuthController@page');

    //============UserController==========================
    Route::post('/user/post_trip', 'UserController@postTrip');
    Route::post('/user/trip_status_change', 'UserController@tripStatusChange');
    Route::get('/user/get_trips', 'UserController@getTrips');
    Route::get('/user/trip_detail', 'UserController@tripDetail');
    Route::post('/user/driver_rating', 'UserController@driverRating');
    Route::post('/user/get_driving_distance', 'UserController@GetDrivingDistance');
    Route::get('/user/get_nearest_driver', 'UserController@getNearestDriver');
    Route::get('/user/trip_payment', 'UserController@tripPayment');
    Route::post('/user/card_payment', 'UserController@cardPayment');

    //============DriverController==========================
    Route::get('/driver/get_trips', 'DriverController@getTrips');
    Route::get('/driver/trip_detail', 'DriverController@tripDetail');
    Route::get('/driver/get_requests', 'DriverController@getRequests');
    Route::post('/driver/trip_status_change', 'DriverController@tripStatusChange');
    Route::post('/driver/trip_cancel', 'DriverController@tripCancel');
    //============ChatController==========================
    Route::post('/send_message', 'ChatController@sendMessage');

    Route::get('/get_chat', 'ChatController@getChat');
});
