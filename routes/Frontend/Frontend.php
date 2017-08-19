<?php

/**
 * Frontend Controllers
 * All route names are prefixed with 'frontend.'.
 */

Route::get('/', 'FrontendController@redirect');
Route::get('dashboard', 'FrontendController@redirect');
Route::get('standings', 'FrontendController@standings')->name('standings');
Route::get('pulls', 'FrontendController@pulls')->name('pulls');
Route::get('favorites', 'FrontendController@favorites')->name('favorites');
Route::get('riders', 'FrontendController@racers')->name('racers');
Route::get('checkpoints', 'FrontendController@checkpoints')->name('checkpoints');
Route::get('results-by-checkpoint', 'FrontendController@resultsByCheckpoint')->name('resultsbycheckpoint');
Route::get('results-by-rider/{racer_no?}', 'FrontendController@resultsByRacer')->name('resultsbyracer');

Route::get('getfavorites', 'FrontendController@getFavorites')->name('getfavorites');
Route::get('clearfavorites', 'FrontendController@clearFavorites')->name('clearfavorites');
Route::get('pushfavorite/{racer_no?}', 'FrontendController@pushFavorite')->name('pushfavorite');

//Route::get('contact', 'ContactController@index')->name('contact');
//Route::post('contact/send', 'ContactController@send')->name('contact.send');
/*
 * These frontend controllers require the user to be logged in
 * All route names are prefixed with 'frontend.'
 */
//Route::group(['middleware' => 'auth'], function () {
//    Route::group(['namespace' => 'User', 'as' => 'user.'], function () {
        /*
         * User Dashboard Specific
         */
        //Route::get('dashboard', 'DashboardController@index')->name('dashboard.user');

        /*
         * User Account Specific
         */
        //Route::get('account', 'AccountController@index')->name('account');

        /*
         * User Profile Specific
         */
        //Route::patch('profile/update', 'ProfileController@update')->name('profile.update');
//    });
//});
