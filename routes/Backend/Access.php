<?php

/**
 * All route names are prefixed with 'admin.access'.
 */
Route::group([
    'prefix' => 'access',
    'as' => 'access.',
    'namespace' => 'Access',
], function () {

    /*
     * User Management
     */
    Route::group([
        'middleware' => 'access.routeNeedsRole:1',
    ], function () {

        Route::get('utilities', 'UtilitiesController@index')->name('utilities');
        Route::get('startevent', 'UtilitiesController@startEvent')->name('startevent');
        Route::get('flushcache', 'UtilitiesController@flushCache')->name('flushcache');


        Route::group(['namespace' => 'User'], function () {
            /*
             * For DataTables
             */
            Route::post('user/get', 'UserTableController')->name('user.get');

            /*
             * User Status'
             */
            Route::get('user/deactivated', 'UserStatusController@getDeactivated')->name('user.deactivated');
            Route::get('user/deleted', 'UserStatusController@getDeleted')->name('user.deleted');

            /*
             * User CRUD
             */
            Route::resource('user', 'UserController');

            /*
             * Specific User
             */
            Route::group(['prefix' => 'user/{user}'], function () {
                // Account
                Route::get('account/confirm/resend', 'UserConfirmationController@sendConfirmationEmail')->name('user.account.confirm.resend');

                // Status
                Route::get('mark/{status}', 'UserStatusController@mark')->name('user.mark')->where(['status' => '[0,1]']);

                // Social
                Route::delete('social/{social}/unlink', 'UserSocialController@unlink')->name('user.social.unlink');

                // Confirmation
                Route::get('confirm', 'UserConfirmationController@confirm')->name('user.confirm');
                Route::get('unconfirm', 'UserConfirmationController@unconfirm')->name('user.unconfirm');

                // Password
                Route::get('password/change', 'UserPasswordController@edit')->name('user.change-password');
                Route::patch('password/change', 'UserPasswordController@update')->name('user.change-password.post');

                // Access
                Route::get('login-as', 'UserAccessController@loginAs')->name('user.login-as');

                // Session
                Route::get('clear-session', 'UserSessionController@clearSession')->name('user.clear-session');
            });

            /*
             * Deleted User
             */
            Route::group(['prefix' => 'user/{deletedUser}'], function () {
                Route::get('delete', 'UserStatusController@delete')->name('user.delete-permanently');
                Route::get('restore', 'UserStatusController@restore')->name('user.restore');
            });
        });

        /*
        * Role Management
        */
        Route::group(['namespace' => 'Role'], function () {
            Route::resource('role', 'RoleController', ['except' => ['show']]);

            //For DataTables
            Route::post('role/get', 'RoleTableController')->name('role.get');
        });

        /*
       * Racer Management
       */
        Route::group(['namespace' => 'Racer'], function () {
            Route::resource('racer', 'RacerController', ['except' => ['show']]);

            //For DataTables
            Route::post('racer/get', 'RacerTableController')->name('racer.get');
        });

        /*
        * Checkpoint Management
        */
        Route::group(['namespace' => 'Checkpoint'], function () {
            Route::resource('checkpoint', 'CheckpointController', ['except' => ['show']]);

            //For DataTables
            Route::get('checkpoint/get', 'CheckpointTableController')->name('checkpoint.get');
        });

        /*
        * Status Code Management
        */
        Route::group(['namespace' => 'Scode'], function () {
            Route::resource('scode', 'ScodeController', ['except' => ['show']]);

            //For DataTables
            Route::get('scode/get', 'ScodeTableController')->name('scode.get');
        });

    });
});
