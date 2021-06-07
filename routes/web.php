<?php

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/',[Controllers\AdminController::class, 'orders'] );
Route::get('/token',[Controllers\AdminController::class, 'index'] );
Route::get('/orders',[Controllers\AdminController::class, 'orders'] );
Route::get('/orders?page={id}',[Controllers\AdminController::class, 'orders'] );
//Route::post('/orders?page={id}', [Controllers\AdminController::class, 'orders'])->name('orders.orders');

//Route::get('/orders?page={id}',[Controllers\AdminController::class, 'orders'] );
//Route::post('/orders?page={id}', [Controllers\AdminController::class, 'orders'])->name('orders.orders');

Route::get('orders/create',[Controllers\AdminController::class, 'create'] );
Route::post('/orders/create',[Controllers\AdminController::class, 'create'] )->name('createOrders');


Route::get('/status',[Controllers\AdminController::class, 'status'] )->name('orders.status');

Route::get('/reports',[Controllers\AdminController::class, 'generateReport'] );
Route::get('/search',[Controllers\AdminController::class, 'search'] );

Route::post('/search',[Controllers\AdminController::class, 'search'] )->name('search');







