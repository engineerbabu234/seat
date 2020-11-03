<?php
Auth::routes();
Route::group(['middleware' => ['auth', 'admin']], function () {

    Route::get('/admin/dashboard', 'Admin\HomeController@index')->name('dashboard');
    Route::get('/admin/profile', 'Admin\ProfileController@show')->name('profile');
    Route::put('/admin/update', 'Admin\ProfileController@update')->name('admin_profile_update');
    Route::put('/admin/update', 'Admin\ProfileController@update')->name('admin_profile_update');
    Route::put('/admin/update_password', 'Admin\ProfileController@updatePassword')->name('admin_update_password');
    Route::put('/admin/update_profile_image', 'Admin\ProfileController@updateProfileImage')->name('update_profile_image');
    Route::put('/admin/update_logo_image', 'Admin\ProfileController@updateLogoImage')->name('update_logo_image');
    Route::name('admin/building/')->group(function () {
        Route::get('admin/building', 'Admin\BuildingController@index')->name('index');
        Route::get('admin/building/add_building', 'Admin\BuildingController@create')->name('add_building');
        Route::post('admin/building/store', 'Admin\BuildingController@store')->name('store');

        Route::get('admin/building/office_list/{id?}', 'Admin\BuildingController@officeList')->name('office_list');
        Route::get('admin/building/show/{id?}', 'Admin\BuildingController@show')->name('show');
        Route::get('admin/building/edit_building/{id?}', 'Admin\BuildingController@edit')->name('edit_building');
        Route::put('admin/building/update/{id?}', 'Admin\BuildingController@update')->name('update');
        Route::get('admin/building/delete/{id}', 'Admin\BuildingController@destroy')->name('destroy');
    });

    Route::name('admin/office/')->group(function () {
        Route::get('admin/office', 'Admin\OfficeController@index')->name('index');
        Route::get('admin/office/add_office', 'Admin\OfficeController@create')->name('add_office');
        Route::post('admin/office/store', 'Admin\OfficeController@store')->name('store');
        Route::get('admin/office/office_details/{id?}', 'Admin\OfficeController@show')->name('office_details');
        Route::get('admin/office/edit_office/{id?}', 'Admin\OfficeController@edit')->name('edit_office');
        Route::put('admin/office/update/{id?}', 'Admin\OfficeController@update')->name('update');
        Route::get('admin/office/delete/{id}', 'Admin\OfficeController@destroy')->name('destroy');
        Route::get('admin/office/delete_seat/{id}', 'Admin\OfficeController@deleteSeat')->name('delete_seat');

    });

    Route::name('admin/reservation/')->group(function () {
        Route::get('admin/reservation/reservation_request', 'Admin\ReservationController@reservationRequest')->name('reservation_request');
        Route::get('admin/reservation/reservation_history', 'Admin\ReservationController@reservationHistory')->name('reservation_history');
        Route::get('admin/reservation/accpted', 'Admin\ReservationController@Accpted')->name('accpted');
        Route::get('admin/reservation/rejected', 'Admin\ReservationController@Rejected')->name('rejected');
        Route::get('admin/reservation/delete/{id}', 'Admin\ReservationController@destroy')->name('destroy');

    });

    Route::name('admin/user/')->group(function () {
        Route::get('admin/user', 'Admin\UserController@index')->name('index');
        Route::get('admin/user/create', 'Admin\UserController@create')->name('create');
        Route::put('admin/user/store', 'Admin\UserController@store')->name('store');
        Route::get('admin/user/document', 'Admin\UserController@document')->name('document');
        Route::get('admin/user/invoice/{id?}', 'Admin\UserController@invoice')->name('invoice');
        Route::get('admin/user/show/{id?}', 'Admin\UserController@show')->name('show');
        Route::get('admin/user/edit/{id?}', 'Admin\UserController@edit')->name('edit');
        Route::put('admin/user/update/{id?}', 'Admin\UserController@update')->name('update');
        Route::delete('admin/user/delete/{id?}', 'Admin\UserController@destroy')->name('destroy');
        Route::put('admin/user/active_status_change', 'Admin\UserController@activeStatusChange')->name('active_status_change');
        Route::get('admin/user/trip_history', 'Admin\UserController@tripHistory')->name('trip_history');
        Route::delete('admin/user/delete/{id?}', 'Admin\UserController@destroy')->name('destroy');
        Route::put('admin/user/approve_status_change', 'Admin\UserController@approveStatusChange')->name('approve_status_change');
    });
});
