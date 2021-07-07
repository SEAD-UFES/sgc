<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\RoleController;

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

Route::get('/', [\App\Http\Controllers\RootController::class, 'rootFork'])->name('root');
Route::get('/login', [\App\Http\Controllers\LoginController::class, 'getLoginForm'])->name('auth.form');
Route::post('/login', [\App\Http\Controllers\LoginController::class, 'authenticate'])->name('auth.login');
Route::get('/logout', [\App\Http\Controllers\LoginController::class, 'logout'])->name('auth.logout');

Route::get('/home', [\App\Http\Controllers\HomeController::class, 'showHome'])->name('home')->middleware('auth');
Route::get('/funding', [\App\Http\Controllers\FundingController::class, 'showFunding'])->name('funding')->middleware('auth');
Route::get('/report', [\App\Http\Controllers\ReportController::class, 'showReport'])->name('report')->middleware('auth');
Route::get('/system', [\App\Http\Controllers\SystemController::class, 'showSystem'])->name('system')->middleware('auth');

Route::resource('courses', CourseController::class)->middleware('auth');
Route::get('/coursetypes/index', [\App\Http\Controllers\CourseTypeController::class, 'index'])->middleware('auth')->name('coursetypes.index');

Route::resource('roles', RoleController::class)->middleware('auth');

Route::resource('users', UserController::class)->middleware('auth');

Route::prefix('/employee')->middleware('auth')->group(function () {
    Route::get('/index',  [\App\Http\Controllers\EmployeeController::class, 'index'])->name('employee.index');
    Route::get('/create',  [\App\Http\Controllers\EmployeeController::class, 'create'])->name('employee.create');
    Route::post('/create',  [\App\Http\Controllers\EmployeeController::class, 'store'])->name('employee.store');
    Route::get('/show/{uuid}',  [\App\Http\Controllers\EmployeeController::class, 'show'])->name('employee.show');
    Route::get('/edit/{uuid}',  [\App\Http\Controllers\EmployeeController::class, 'edit'])->name('employee.edit');
});

Route::fallback([\App\Http\Controllers\FallbackController::class, 'fallback']);
