<?php
Auth::routes();
Route::get('/admin', 'Auth\LoginController@showLoginFormAdmin')->name('admin-login');
Route::get('/', 'Auth\LoginController@showLoginForm')->name('');
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

Route::get('/verify/email/{id?}', 'HomeController@verifyEmail')->name('email.verification');

Route::get('/sign_up', 'Front_End\UserController@index');
Route::post('/sign_process', 'Front_End\UserController@signProcess');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/profile', 'Front_End\ProfileController@show');
    Route::put('/update_profile', 'Front_End\ProfileController@update')->name('update_profile');
    Route::put('/update_profile_image', 'Front_End\ProfileController@updateProfileImage')->name('update_profile_image');
    Route::put('/update_password', 'Front_End\ProfileController@updatePassword')->name('update_password');
    Route::put('/seat_reservation', 'Front_End\HomeController@seatReservation')->name('seat_reservation');
    Route::get('/get_history', 'Front_End\HomeController@getHistory');
    Route::get('/reservation_status_change', 'Front_End\HomeController@reservationStatusChange')->name('reservation_status_change');

    Route::get('/index', 'Front_End\HomeController@index')->name('index');
    Route::get('/office_list', 'Front_End\HomeController@officeList');
    Route::get('/get_building', 'Front_End\HomeController@getBuilding');
    Route::get('/get_office_list', 'Front_End\HomeController@getOfficeList');
    Route::get('/reserve_seat', 'Front_End\HomeController@reserveSeat');
    Route::get('/history', 'Front_End\HomeController@history');
    Route::get('/get_seat_list', 'Front_End\HomeController@getSeatList');
    Route::get('/assets_list', 'Front_End\HomeController@AssetsList');
    Route::get('/get_assets_list', 'Front_End\HomeController@getAssetsList');
});
