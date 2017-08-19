<?php

/**
 * All route names are prefixed with 'admin.'.
 */

Route::get('/', 'ChecktimeController@redirect');
Route::get('timeentry', 'ChecktimeController@index')->name('timeentry');
Route::resource('checktimes', 'ChecktimeController',['only' => ['store', 'destroy']]);
Route::resource('pulls', 'PullController',['only' => ['index', 'show', 'store', 'destroy']]);
Route::get('winlink', 'WinlinkController@importWinlink')->name('importwinlink');
Route::post('winlink', 'WinlinkController@postWinlink')->name('postwinlink');
Route::get('errorcheck', 'WinlinkController@errorCheck')->name('errorcheck');
Route::get('reports/importlog', 'ReportController@importLog')->name('reports.importlog');
Route::get('reports/standingsreport', 'ReportController@standingsReport')->name('reports.standingsreport');
