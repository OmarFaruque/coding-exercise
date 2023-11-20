<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Auth::routes();


Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware(['auth', 'admin'])->group(function () {
    //Admin Routes 
    Route::get('new-restraction', 'App\Http\Controllers\TransactionsController@create')->name('new-restraction');
    Route::post('new-entry', 'App\Http\Controllers\TransactionsController@store')->name('new-transactions');
    Route::delete('delete-transaction/{id}', 'App\Http\Controllers\TransactionsController@destroy')->name('delete-transaction');
    
    //Report
    Route::get('reports', 'App\Http\Controllers\TransactionsController@reports')->name('reports');
    Route::post('reports', 'App\Http\Controllers\TransactionsController@viewreport')->name('transaction-report');

    // Payment Route
    Route::resource('payment', App\Http\Controllers\PaymentController::class);
    
});


Route::middleware(['auth'])->group(function () {
    //Admin Routes 
    Route::get('view-transaction/{id}', 'App\Http\Controllers\TransactionsController@show')->name('view-transaction');
});
