<?php

Route::group(['middleware' => ['auth', 'admin']], function () {

    Route::group(['prefix' => 'admin'], function () {

        Route::get('dashboard', 'HomeController@index')->name('dashboard');
        Route::get('profile', 'ProfileController@show')->name('profile');
        Route::put('update', 'ProfileController@update')->name('admin_profile_update');
        Route::put('update', 'ProfileController@update')->name('admin_profile_update');
        Route::put('update_password', 'ProfileController@updatePassword')->name('admin_update_password');
        Route::put('update_profile_image', 'ProfileController@updateProfileImage')->name('update_profile_image');
        Route::put('update_logo_image', 'ProfileController@updateLogoImage')->name('update_logo_image');

        // building
        Route::group(['prefix' => 'building'], function () {
            Route::get('/', 'BuildingController@index')->name('index');
            Route::get('add_building', 'BuildingController@create')->name('add_building');
            Route::post('store', 'BuildingController@store')->name('store');

            Route::get('office_list/{id?}', 'BuildingController@officeList')->name('office_list');
            Route::get('show/{id?}', 'BuildingController@show')->name('show');
            Route::get('edit_building/{id?}', 'BuildingController@edit')->name('edit_building');
            Route::put('update/{id?}', 'BuildingController@update')->name('update');
            Route::get('delete/{id}', 'BuildingController@destroy')->name('destroy');
        });

        // office
        Route::group(['prefix' => 'office'], function () {
            Route::get('/', 'OfficeController@index')->name('index');
            Route::get('add_office', 'OfficeController@create')->name('add_office');
            Route::post('store', 'OfficeController@store')->name('store');
            Route::get('office_details/{id?}', 'OfficeController@show')->name('office_details');
            Route::get('edit_office/{id?}', 'OfficeController@edit')->name('edit_office');
            Route::put('update/{id?}', 'OfficeController@update')->name('update');
            Route::get('delete/{id}', 'OfficeController@destroy')->name('destroy');
            Route::get('delete_seat/{id}', 'OfficeController@deleteSeat')->name('delete_seat');

            // office asset
            Route::group(['prefix' => 'asset'], function () {
                Route::get('/', [
                    'as' => 'office.asset',
                    'uses' => 'OfficeAssetController@index',
                ]);

                Route::post('/add', [
                    'as' => 'office.asset.add',
                    'uses' => 'OfficeAssetController@addAsset',
                ]);

                Route::post('/save', [
                    'as' => 'office.asset.save',
                    'uses' => 'OfficeAssetController@saveOfficeAsset',
                ]);

                Route::post('/edit/{asset_id}', [
                    'as' => 'office.asset.edit',
                    'uses' => 'OfficeAssetController@editOfficeAsset',
                ]);

                Route::post('/update/{asset_id}', [
                    'as' => 'office.asset.update',
                    'uses' => 'OfficeAssetController@updateOfficeAsset',
                ]);

                Route::post('/delete/{asset_id}', [
                    'as' => 'office.asset.delete',
                    'uses' => 'OfficeAssetController@deleteAsset',
                ]);

                Route::get('/getoffice/{id}', [
                    'as' => 'office.asset.getoffices',
                    'uses' => 'OfficeAssetController@getoffices',
                ]);
            });
        });

        // reservation
        Route::group(['prefix' => 'reservation'], function () {
            Route::get('reservation_request', 'ReservationController@reservationRequest')->name('reservation_request');
            Route::get('reservation_history', 'ReservationController@reservationHistory')->name('reservation_history');
            Route::get('accpted', 'ReservationController@Accpted')->name('accpted');
            Route::get('rejected', 'ReservationController@Rejected')->name('rejected');
            Route::get('delete/{id}', 'ReservationController@destroy')->name('destroy');
        });

        // user
        Route::group(['prefix' => 'user'], function () {
            Route::get('/', 'UserController@index')->name('index');
            Route::get('create', 'UserController@create')->name('create');
            Route::put('store', 'UserController@store')->name('store');
            Route::get('document', 'UserController@document')->name('document');
            Route::get('invoice/{id?}', 'UserController@invoice')->name('invoice');
            Route::get('show/{id?}', 'UserController@show')->name('show');
            Route::get('edit/{id?}', 'UserController@edit')->name('edit');
            Route::put('update/{id?}', 'UserController@update')->name('update');
            Route::delete('delete/{id?}', 'UserController@destroy')->name('destroy');
            Route::put('active_status_change', 'UserController@activeStatusChange')->name('active_status_change');
            Route::get('trip_history', 'UserController@tripHistory')->name('trip_history');
            Route::delete('delete/{id?}', 'UserController@destroy')->name('destroy');
            Route::put('approve_status_change', 'UserController@approveStatusChange')->name('approve_status_change');
        });
    });
});
