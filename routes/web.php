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

Route::get('/', [\App\Http\Controllers\LoginController::class, 'getLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\LoginController::class, 'authenticate'])->name('auth.login');
Route::get('/logout', [\App\Http\Controllers\LoginController::class, 'logout'])->name('auth.logout');

Route::get('/home', function () {
    return view('home');
})->name('home')->middleware('auth');

Route::fallback([\App\Http\Controllers\FallbackController::class, 'fallback']);
