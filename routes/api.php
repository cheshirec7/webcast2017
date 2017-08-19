<?php

//use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('getstandings', 'ApiController@getStandings')->name('standings.ajax.get');
Route::get('getpulls', 'ApiController@getPulls')->name('pulls.ajax.get');
Route::get('getfavorites', 'ApiController@getFavorites')->name('favorites.ajax.get');
Route::get('getracers', 'ApiController@getRacers')->name('racers.ajax.get');

Route::get('getcheckpointswithtimes/{racer_no}', 'ApiController@getCheckpointsWithTimes');
Route::get('getracernumbers', 'ApiController@getRacerNumbers');
Route::get('getuserroles/{user_id}', 'ApiController@getUserRoles');
Route::get('getpull/{racer_no}', 'ApiController@getPull');
Route::get('getresultsbycheckpoint/{checkpoint_id}/{checkpoint_type}', 'ApiController@getResultsByCheckpoint');
