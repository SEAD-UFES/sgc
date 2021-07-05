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

Route::get('/home', [\App\Http\Controllers\HomeController::class, 'showHome'])->name('home')->middleware('auth');
Route::get('/funding', [\App\Http\Controllers\FundingController::class, 'showFunding'])->name('funding')->middleware('auth');
Route::get('/report', [\App\Http\Controllers\ReportController::class, 'showReport'])->name('report')->middleware('auth');
Route::get('/system', [\App\Http\Controllers\SystemController::class, 'showSystem'])->name('system')->middleware('auth');



Route::prefix('/role')->middleware('auth')->group(function () {
    Route::get('/index',  [\App\Http\Controllers\RoleController::class, 'index'])->name('role.index');
    Route::get('/create',  [\App\Http\Controllers\RoleController::class, 'create'])->name('role.create');
    Route::post('/create',  [\App\Http\Controllers\RoleController::class, 'store'])->name('role.store');
    Route::get('/show/{uuid}',  [\App\Http\Controllers\RoleController::class, 'show'])->name('role.show');
    Route::get('/edit/{uuid}',  [\App\Http\Controllers\RoleController::class, 'edit'])->name('role.edit');
});

Route::prefix('/user')->middleware('auth')->group(function () {
    Route::get('/index',  [\App\Http\Controllers\UserController::class, 'index'])->name('user.index');
    Route::get('/create',  [\App\Http\Controllers\UserController::class, 'create'])->name('user.create');
    Route::post('/create',  [\App\Http\Controllers\UserController::class, 'store'])->name('user.store');
    Route::get('/show/{uuid}',  [\App\Http\Controllers\UserController::class, 'show'])->name('user.show');
    Route::get('/edit/{uuid}',  [\App\Http\Controllers\UserController::class, 'edit'])->name('user.edit');
    Route::patch('/update/{uuid}',  [\App\Http\Controllers\UserController::class, 'update'])->name('user.update');
    Route::get('/destroy/{uuid}',  [\App\Http\Controllers\UserController::class, 'destroy'])->name('user.destroy');
});

Route::prefix('/employee')->middleware('auth')->group(function () {
    Route::get('/index',  [\App\Http\Controllers\EmployeeController::class, 'index'])->name('employee.index');
    Route::get('/create',  [\App\Http\Controllers\EmployeeController::class, 'create'])->name('employee.create');
    Route::post('/create',  [\App\Http\Controllers\EmployeeController::class, 'store'])->name('employee.store');
    Route::get('/show/{uuid}',  [\App\Http\Controllers\EmployeeController::class, 'show'])->name('employee.show');
    Route::get('/edit/{uuid}',  [\App\Http\Controllers\EmployeeController::class, 'edit'])->name('employee.edit');
});

Route::prefix('/course')->middleware('auth')->group(function () {
    Route::get('/index',  [\App\Http\Controllers\CourseController::class, 'index'])->name('course.index');
    Route::get('/create',  [\App\Http\Controllers\CourseController::class, 'create'])->name('course.create');
    Route::post('/create',  [\App\Http\Controllers\CourseController::class, 'store'])->name('course.store');
    Route::get('/show/{uuid}',  [\App\Http\Controllers\CourseController::class, 'show'])->name('course.show');
    Route::get('/edit/{uuid}',  [\App\Http\Controllers\CourseController::class, 'edit'])->name('course.edit');
});


Route::fallback([\App\Http\Controllers\FallbackController::class, 'fallback']);
