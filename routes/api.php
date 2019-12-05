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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->post('logout', 'LoginControllerAPI@logout');

Route::get('home', 'api\WalletControllerAPI@countWallets');
Route::get('users', 'api\UserControllerAPI@index');
Route::get('users/{id}', 'api\UserControllerAPI@show');
Route::get('users/emailavailable', 'api\UserControllerAPI@emailAvailable');

Route::post('login', 'api\LoginControllerAPI@login');
Route::post('users', 'api\UserControllerAPI@store');

Route::put('users/{id}', 'api\UserControllerAPI@update');

Route::delete('users{id}', 'api\UserControllerAPI@destroy');
