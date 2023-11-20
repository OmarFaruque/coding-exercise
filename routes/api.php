<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('token', 'App\Http\Controllers\api\UserController@login');

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('users', 'App\Http\Controllers\api\TransactionsController@create');
    Route::get('view-transaction/{id}', 'App\Http\Controllers\api\TransactionsController@show');
    Route::post('new-transaction', 'App\Http\Controllers\api\TransactionsController@store');
    Route::delete('delete-transaction/{id}', 'App\Http\Controllers\api\TransactionsController@destroy');

    //Report
    Route::post('reports', 'App\Http\Controllers\api\TransactionsController@viewreport');

    Route::resource('payment', App\Http\Controllers\api\PaymentController::class);
    
});
