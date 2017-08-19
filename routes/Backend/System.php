<?php

/**
 * All route names are prefixed with 'admin.system'.
 */
Route::group([
    'prefix'     => 'system',
    'as'         => 'system.',
    'namespace'  => 'System',
], function () {

    /*
     * System Management
     */
    Route::group([
        'middleware' => 'access.routeNeedsRole:1',
    ], function () {
        /*
        * Setup
        */
        Route::group(['namespace' => 'Utilities'], function () {
            Route::resource('setup', 'UtilitiesController', ['except' => ['show']]);

        });
    });
});
