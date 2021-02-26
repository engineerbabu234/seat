<?php

Auth::routes();
Route::get('/admin', 'Auth\LoginController@showLoginFormAdmin')->name('admin-login');

Route::get('/', 'Auth\LoginController@showLoginForm')->name('');

Route::get('seat_logout', '\App\Http\Controllers\Auth\LoginController@seat_logout');
Route::get('/cleaner', 'Auth\LoginController@showCleanerLoginForm')->name('');
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

Route::get('/verify/email/{id?}', 'HomeController@verifyEmail')->name('email.verification');

Route::get('/sign_up', 'Front_End\UserController@index');
Route::post('/sign_process', 'Front_End\UserController@signProcess');

Route::get('/seatlogin', 'Auth\LoginController@SeatrequestLoginForm')->name('');

// Seat Label
Route::group(['prefix' => 'seatrequest'], function () {
    Route::get('NFCCode=1234456', 'Front_End\Seatrequest@nfccode')->name('nfccode');
    Route::get('QRCode/{id?}', 'Front_End\Seatrequest@qrcode')->name('qrcode');
    Route::get('/', 'Front_End\Seatrequest@browser')->name('browser');
    Route::post('/challenge', 'Front_End\Seatrequest@challenge')->name('challenge');
    Route::post('/message', 'Front_End\Seatrequest@message')->name('message');
    Route::get('/filter_office_list/{id}', 'Front_End\Seatrequest@filterofficeList');
    Route::get('/filter_office_assets_list/{id}', 'Front_End\Seatrequest@filterofficeassetsList');
    Route::get('/filter_seat_list/{id}', 'Front_End\Seatrequest@filterseatslist');
    Route::post('/upload_code', 'Front_End\Seatrequest@upload_code')->name('upload_code');
    Route::get('/checkin/{id}', 'Front_End\Seatrequest@checkin');
    Route::get('/checkout/{id}', 'Front_End\Seatrequest@checkout');
    Route::post('/cleanseat/{id}', 'Front_End\Seatrequest@cleanseat');
    Route::post('/check_user_process', 'Front_End\Seatrequest@check_user_process');
});

Route::group(['middleware' => ['auth']], function () {

    Route::get('/profile', 'Front_End\ProfileController@show');
    Route::put('/update_profile', 'Front_End\ProfileController@update')->name('update_profile');
    Route::put('/update_profile_image', 'Front_End\ProfileController@updateProfileImage')->name('update_profile_image');
    Route::put('/update_password', 'Front_End\ProfileController@updatePassword')->name('update_password');
    Route::put('/seat_reservation', 'Front_End\HomeController@seatReservation')->name('seat_reservation');
    Route::get('/get_history', 'Front_End\HomeController@getHistory');
    Route::get('/reservation_status_change', 'Front_End\HomeController@reservationStatusChange')->name('reservation_status_change');
    Route::post('/update_mobile_number', 'Front_End\ProfileController@update_mobile_number');

    Route::get('get_new_time', 'Front_End\HomeController@get_new_time')->name('get_new_time');
    Route::get('/index', 'Front_End\HomeController@index')->name('index');
    Route::get('/office_list', 'Front_End\HomeController@officeList');
    Route::get('/get_building', 'Front_End\HomeController@getBuilding');
    Route::get('/get_office_list', 'Front_End\HomeController@getOfficeList');
    Route::get('/reserve_seat', 'Front_End\HomeController@reserveSeat');
    Route::get('/reservation', 'Front_End\HomeController@reservation');
    Route::get('/get_seat_list', 'Front_End\HomeController@getSeatList');
    Route::get('/assets_list', 'Front_End\HomeController@AssetsList');
    Route::get('/get_assets_list', 'Front_End\HomeController@getAssetsList');
    Route::post('/getofficeassetsinfo/{id}', 'Front_End\HomeController@getofficeassetsinfo');
    Route::get('/getassetsdetails/{id}', 'Front_End\HomeController@getassetsdetails');
    Route::post('/bookOfficeSeats', 'Front_End\HomeController@bookOfficeSeats');
    Route::get('/getLogicQuestions/{id}/{dots_id}', 'Front_End\HomeController@getLogicQuestions');
    Route::post('/checklogic', 'Front_End\HomeController@checklogic');
    Route::get('/getAssetsSeats/{asset_id}/{dots_id}', 'Front_End\HomeController@getAssetsSeats');
    Route::post('/updateassets_image/{asset_id}', 'Front_End\HomeController@updateassets_image');
    Route::get('/questionaries', 'Front_End\HomeController@questionaries');
    Route::get('/getuserLogicQuestions/{id}', 'Front_End\HomeController@getuserLogicQuestions');
    Route::post('/check_user_seat_booking/{asset_id}/{dots_id}', 'Front_End\HomeController@check_user_seat_booking');

    Route::get('/filter_office_list/{id}', 'Front_End\HomeController@filterofficeList');
    Route::get('/filter_office_assets_list/{id}', 'Front_End\HomeController@filterofficeassetsList');
    Route::get('/filter_office_assets_info/{id}', 'Front_End\HomeController@filterofficeassetsinfo');
    Route::get('/filter_office_assets_type_info/{id}', 'Front_End\HomeController@filterofficeassetstypeinfo');
    Route::get('/search_seat', 'Front_End\HomeController@search_seat');
    Route::post('/get_user_questionarie_result', 'Front_End\HomeController@get_user_questionarie_result');
    Route::post('/seat_check_in', 'Front_End\HomeController@seat_check_in');
    Route::post('/seat_view', 'Front_End\HomeController@seat_view');
    Route::post('/get_reservation_info', 'Front_End\HomeController@get_reservation_info');
    Route::get('/get_assets_info/{id}', 'Front_End\HomeController@get_assets_info');

    Route::get('/contracts', 'Front_End\ContractController@index');
    Route::post('/add_contract', 'Front_End\ContractController@add_contract');
    Route::get('/get_user_contract_sign_result/{id}', 'Front_End\ContractController@get_user_contract_sign_result');
});

Route::get('invite/user/registration/{id}', 'Front_End\HomeController@inviteUserRegistrationForm');
Route::post('invite/user/registration', 'Front_End\HomeController@inviteUserRegistrationStore');
