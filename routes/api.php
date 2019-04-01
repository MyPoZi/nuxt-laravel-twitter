<?php

use Illuminate\Http\Request;

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

Route::get('/', function(){ return 'helloworld';});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('oauth/twitter/redirect', 'Auth\LoginController@redirectToProvider');
Route::get('oauth/twitter/callback', 'Auth\LoginController@handleProviderCallback');
