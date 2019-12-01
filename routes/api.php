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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::middleware('auth:api')->group(function (){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

});

Route::post('login', 'LoginControllerAPI@login');
Route::middleware('auth:api')->post('logout', 'LoginControllerAPI@logout');

Route::get('home', 'WalletControllerAPI@countWallets');

Route::middleware('auth:api')->get('users/cancel/{id}', 'UserControllerAPI@show');  //alterar rota
Route::middleware('auth:api')->get('users/me', 'UserControllerAPI@myProfile');
Route::middleware('auth:api')->put('users/{id}', 'UserControllerAPI@update');
Route::middleware('auth:api')->patch('users/password', 'UserControllerAPI@alterarPassword');

Route::middleware('auth:api')->post('movements/credit', 'MovementControllerAPI@createCredit');


