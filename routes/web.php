<?php

use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\product\ProductController;
use App\Http\Controllers\sale\SaleController;
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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'store'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');


Route::get('/productos', [ProductController::class, 'index'])->name('product.index');
Route::get('/sales', [SaleController::class , 'index'])->name('sales.index');