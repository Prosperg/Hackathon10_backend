<?php

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

Route::get('/', function () {
    // return view('welcome');
    // return view('product');
    return view('verify');
});

// Route::get('/form', function () {return view('logForm');});

// Route::post('/login',[App\Http\Controllers\TestController::class,'login'])->name('log');
// Route::put('/product-updated',[App\Http\Controllers\TestController::class,'update'])->name('updated');
