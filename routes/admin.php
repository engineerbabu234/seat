<?php

Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::group(['prefix' => 'admin'], function () {


        Route::get('dashboard', 'Admin\HomeController@index')->name('dashboard');
        Route::get('profile', 'Admin\ProfileController@show')->name('profile');
        Route::put('update', 'Admin\ProfileController@update')->name('admin_profile_update');
        Route::put('update', 'Admin\ProfileController@update')->name('admin_profile_update');
        Route::put('update_password', 'Admin\ProfileController@updatePassword')->name('admin_update_password');
        Route::put('update_profile_image', 'Admin\ProfileController@updateProfileImage')->name('update_profile_image');
        Route::put('update_logo_image', 'Admin\ProfileController@updateLogoImage')->name('update_logo_image');

        Route::get('invite/users', 'Admin\HomeController@inviteUsers')->name('invite.users');
        Route::get('create/invitation/link', 'Admin\HomeController@createInvitatinLink')->name('create.invitation.link');
        Route::post('store/invitation/link', 'Admin\HomeController@storeInvitationLink')->name('store.invitation.link');
        Route::get('get_new_time', 'HomeController@get_new_time')->name('get_new_time');
       

        // Seat Label
        Route::group(['prefix' => 'settings'], function () {
            Route::get('/', 'SettingsController@index')->name('index');
            Route::post('store', 'SettingsController@store')->name('store');
            Route::post('update/{id?}', 'SettingsController@update')->name('update');
            Route::get('show/{id?}', 'SettingsController@show')->name('show');
            Route::get('edit_settings/{id?}', 'SettingsController@edit')->name('edit_settings');

        });

        // Seat Label
        Route::group(['prefix' => 'seat_label'], function () {
            Route::get('/', 'SeatLabelController@index')->name('index');
            Route::get('add_seat_label', 'SeatLabelController@create')->name('add_seat_label');
            Route::post('store', 'SeatLabelController@store')->name('store');
            Route::get('show/{id?}', 'SeatLabelController@show')->name('show');
            Route::get('edit_seat_label/{id?}', 'SeatLabelController@edit')->name('edit_seat_label');
            Route::post('update/{id?}', 'SeatLabelController@update')->name('update');
            Route::get('/filter_office_list/{id}', 'SeatLabelController@filterofficeList');
            Route::get('/filter_office_assets_list/{id}', 'SeatLabelController@filterofficeassetsList');
            Route::get('/filter_seat_list/{id}', 'SeatLabelController@filterseatslist');
            Route::get('get_deploy_info/{id?}', 'SeatLabelController@get_deploy_info')->name('get_deploy_info');
            Route::get('deploy_label', 'SeatLabelController@deploy_label')->name('deploy_label');
            Route::get('change_deploy_seat/{id?}', 'SeatLabelController@change_deploy_seat')->name('change_deploy_seat');

        });

        // building
        Route::group(['prefix' => 'building'], function () {
            Route::get('/', 'Admin\BuildingController@index')->name('index');
            Route::get('add_building', 'BuildingController@create')->name('add_building');
            Route::post('store', 'BuildingController@store')->name('store');
            Route::get('office_list/{id?}', 'BuildingController@officeList')->name('office_list');
            Route::get('show/{id?}', 'BuildingController@show')->name('show');
            Route::get('edit_building/{id?}', 'BuildingController@edit')->name('edit_building');
            Route::post('update/{id?}', 'BuildingController@update')->name('update');
            Route::post('delete/{id}', 'BuildingController@destroy')->name('destroy');
        });

        // office
        Route::group(['prefix' => 'office'], function () {

            // office asset
            Route::group(['prefix' => 'asset'], function () {
                Route::get('/{id?}', [
                    'as' => 'office.asset',
                    'uses' => 'Admin\OfficeAssetController@index',
                ]);

                Route::post('/save', [
                    'as' => 'office.asset.save',
                    'uses' => 'Admin\OfficeAssetController@saveOfficeAsset',
                ]);

                Route::post('/edit/{asset_id}', [
                    'as' => 'office.asset.edit',
                    'uses' => 'Admin\OfficeAssetController@editOfficeAsset',
                ]);

                Route::post('/update/{asset_id}', [
                    'as' => 'office.asset.update',
                    'uses' => 'Admin\OfficeAssetController@updateOfficeAsset',
                ]);

                Route::post('/delete/{asset_id}', [
                    'as' => 'office.asset.delete',
                    'uses' => 'Admin\OfficeAssetController@deleteAsset',
                ]);

                Route::get('/getoffice/{id}', [
                    'as' => 'office.asset.getoffices',
                    'uses' => 'Admin\OfficeAssetController@getoffices',
                ]);

                Route::get('/getofficeassets/{id}', [
                    'as' => 'office.asset.getofficeassets',
                    'uses' => 'Admin\OfficeAssetController@getofficeassets',
                ]);

                Route::post('/addseat', [
                    'as' => 'office.asset.addseat',
                    'uses' => 'Admin\OfficeAssetController@addseat',
                ]);

                Route::get('/getofficeassetsinfo/{id}', [
                    'as' => 'office.asset.getofficeassetsinfo',
                    'uses' => 'Admin\OfficeAssetController@getofficeassetsinfo',
                ]);

                Route::post('/updateassets_image/{asset_id}', [
                    'as' => 'office.asset.updateassets_image',
                    'uses' => 'Admin\OfficeAssetController@updateassets_image',
                ]);

                Route::post('/edit_seats/{seat_id}', [
                    'as' => 'office.asset.edit_seats',
                    'uses' => 'Admin\OfficeAssetController@edit_seats',
                ]);

                Route::post('/updateSeat/{seat_id}', [
                    'as' => 'office.asset.updateSeat',
                    'uses' => 'Admin\OfficeAssetController@updateSeat',
                ]);

                Route::post('/deleteSeat/{seat_id}', [
                    'as' => 'office.asset.deleteSeat',
                    'uses' => 'Admin\OfficeAssetController@deleteSeat',
                ]);

                Route::get('/getAssetsSeats/{asset_id}/{dots_id}', [
                    'as' => 'office.asset.getAssetsSeats',
                    'uses' => 'Admin\OfficeAssetController@getAssetsSeats',
                ]);

                Route::post('/question_logic/{id?}', [
                    'as' => 'office.asset.question_logic',
                    'uses' => 'Admin\OfficeAssetController@question_logic',
                ]);

                Route::post('/save_question_logic/', [
                    'as' => 'office.asset.deleteSeat',
                    'uses' => 'Admin\OfficeAssetController@save_question_logic',
                ]);

                Route::post('/deleteSeat/{asset_id}/{dots_id}', [
                    'as' => 'office.asset.deleteSeat',
                    'uses' => 'Admin\OfficeAssetController@deleteSeat',
                ]);

                Route::post('/get_question_list/', [
                    'as' => 'office.asset.get_question_list',
                    'uses' => 'Admin\OfficeAssetController@get_question_list',
                ]);

                Route::get('/block_notification/{asset_id}/{dots_id}', [
                    'as' => 'office.asset.block_notification',
                    'uses' => 'OfficeAssetController@block_notification',
                ]);

            });

            Route::get('/{building_id?}', 'Admin\OfficeController@index')->name('index');
            Route::get('add_office', 'Admin\OfficeController@create')->name('add_office');
            Route::post('store', 'Admin\OfficeController@store')->name('store');
            Route::get('office_details/{id?}', 'Admin\OfficeController@show')->name('office_details');
            Route::get('edit_office/{id?}', 'Admin\OfficeController@edit')->name('edit_office');
            Route::post('update/{id?}', 'Admin\OfficeController@update')->name('update');
            Route::get('delete/{id}', 'Admin\OfficeController@destroy')->name('destroy');
            Route::get('delete_seat/{id}', 'Admin\OfficeController@deleteSeat')->name('delete_seat');
        });

        // office
        Route::group(['prefix' => 'question'], function () {

            Route::get('/{quesionaire_id?}', 'Admin\QuestionsController@index')->name('index');
            Route::get('add_question', 'Admin\QuestionsController@create')->name('add_question');
            Route::post('store', 'Admin\QuestionsController@store')->name('store');
            Route::get('question_details/{id?}', 'Admin\QuestionsController@show')->name('question_details');
            Route::get('edit_question/{id?}', 'Admin\QuestionsController@edit')->name('edit_question');
            Route::post('update/{id?}', 'Admin\QuestionsController@update')->name('update');
            Route::get('delete/{id}', 'Admin\QuestionsController@destroy')->name('destroy');

        });

        // Api connections
        Route::group(['prefix' => 'apiconnections'], function () {
            Route::get('/', 'ApiConnectionsController@index')->name('index');
            Route::get('get_api_provider_list/{id?}', 'ApiConnectionsController@get_api_provider_list')->name('get_api_provider_list');
            Route::post('store', 'ApiConnectionsController@store')->name('store');
            Route::get('edit_apiconnections/{id?}', 'ApiConnectionsController@edit')->name('edit_apiconnections');
            Route::post('update/{id?}', 'ApiConnectionsController@update')->name('update');
            Route::get('delete/{id}', 'ApiConnectionsController@destroy')->name('destroy');
        });

        // quesionaire
        Route::group(['prefix' => 'quesionaire'], function () {


            Route::get('/', 'Admin\QuesionaireController@index')->name('index');
            Route::post('store', 'Admin\QuesionaireController@store')->name('store');
            Route::get('quesionaire_details/{id?}', 'Admin\QuesionaireController@show')->name('quesionaire_details');
            Route::get('edit_quesionaire/{id?}', 'Admin\QuesionaireController@edit')->name('edit_quesionaire');
            Route::post('update/{id?}', 'Admin\QuesionaireController@update')->name('update');
            Route::get('delete/{id}', 'Admin\QuesionaireController@destroy')->name('destroy');
            Route::get('destroy_questions/{id}', 'QuesionaireController@destroy_questions');

        });

        // reservation
        Route::group(['prefix' => 'reservation'], function () {
            Route::get('reservation_request', 'Admin\ReservationController@reservationRequest')->name('reservation_request');
            Route::get('reservation_history', 'Admin\ReservationController@reservationHistory')->name('reservation_history');
            Route::get('accpted', 'Admin\ReservationController@Accpted')->name('accpted');
            Route::get('rejected', 'Admin\ReservationController@Rejected')->name('rejected');
            Route::get('delete/{id}', 'Admin\ReservationController@destroy')->name('destroy');
        });

        // user
        Route::group(['prefix' => 'user'], function () {
            Route::get('/', 'Admin\UserController@index')->name('index');
            Route::get('create', 'Admin\UserController@create')->name('create');
            Route::put('store', 'Admin\UserController@store')->name('store');
            Route::get('document', 'Admin\UserController@document')->name('document');
            Route::get('invoice/{id?}', 'Admin\UserController@invoice')->name('invoice');
            Route::get('show/{id?}', 'Admin\UserController@show')->name('show');
            Route::get('edit/{id?}', 'Admin\UserController@edit')->name('edit');
            Route::put('update/{id?}', 'Admin\UserController@update')->name('update');
            Route::delete('delete/{id?}', 'Admin\UserController@destroy')->name('destroy');
            Route::put('active_status_change', 'Admin\UserController@activeStatusChange')->name('active_status_change');
            Route::get('trip_history', 'Admin\UserController@tripHistory')->name('trip_history');
            Route::delete('delete/{id?}', 'Admin\UserController@destroy')->name('destroy');
            Route::put('approve_status_change', 'Admin\UserController@approveStatusChange')->name('approve_status_change');
        });
    });
});
