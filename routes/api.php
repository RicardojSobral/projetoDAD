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
Route::get('wallet/{id}/balance', 'WalletControllerAPI@getBalance');
Route::get('users/emailavailable', 'api\UserControllerAPI@emailAvailable');

Route::post('users/create', 'UserControllerAPI@store');
Route::middleware('auth:api')->get('users/cancel/{id}', 'UserControllerAPI@show');
Route::middleware('auth:api')->get('users/me', 'UserControllerAPI@myProfile');
Route::middleware('auth:api')->put('users/{id}', 'UserControllerAPI@update');
Route::middleware('auth:api')->patch('users/password', 'UserControllerAPI@alterarPassword');
Route::middleware('auth:api')->post('users/createOpAdmin', 'UserControllerAPI@storeOpAdmin');
Route::middleware('auth:api')->post('users/filter', 'UserControllerAPI@getFilteredUsers');
Route::middleware('auth:api')->put('users/deactivate/{id}', 'UserControllerAPI@deactivateUser');
Route::middleware('auth:api')->put('users/activate/{id}', 'UserControllerAPI@activateUser');
Route::middleware('auth:api')->delete('users/{id}', 'UserControllerAPI@destroy');

Route::middleware('auth:api')->get('users/stats/totals/{id}', 'UserControllerAPI@getTotals');
Route::middleware('auth:api')->get('users/stats/expensesByCategory/{id}', 'UserControllerAPI@expensesByCategory');
Route::middleware('auth:api')->get('users/stats/incomesByCategory/{id}', 'UserControllerAPI@incomesByCategory');
Route::middleware('auth:api')->get('users/stats/balanceThroughTime/{id}', 'UserControllerAPI@balanceThroughTime');
Route::middleware('auth:api')->get('users/stats/expensesThroughTime/{id}', 'UserControllerAPI@expensesThroughTime');
Route::middleware('auth:api')->get('users/stats/incomesThroughTime/{id}', 'UserControllerAPI@incomesThroughTime');

Route::middleware('auth:api')->get('admin/stats/numberActiveUsers', 'UserControllerAPI@getNumberActiveUsers');
Route::middleware('auth:api')->get('admin/stats/movementsThroughTime', 'UserControllerAPI@getMovementsThroughTime');
Route::middleware('auth:api')->get('admin/stats/externalIncomeThroughTime', 'UserControllerAPI@getExternalIncomeThroughTimeThroughTime');
Route::middleware('auth:api')->get('admin/stats/internalTransfersThroughTime', 'UserControllerAPI@getInternalTransfersThroughTimeThroughTime');
Route::middleware('auth:api')->get('admin/stats/usersRegisteredThroughTime', 'UserControllerAPI@getUsersRegisteredThroughTime');


Route::middleware('auth:api')->post('movements/credit', 'MovementControllerAPI@createCredit');
Route::middleware('auth:api')->post('movements/debit', 'MovementControllerAPI@createDebit');
Route::middleware('auth:api')->post('movements/filter', 'MovementControllerAPI@getFilteredMovements');
Route::middleware('auth:api')->post('movements/sendNotificationEmail', 'MovementControllerAPI@sendNotificationEmail');
Route::middleware('auth:api')->put('movements/{id}', 'MovementControllerAPI@update');

Route::middleware('auth:api')->get('categories/{type}', 'CategoryControllerAPI@getCategoriesByType');
