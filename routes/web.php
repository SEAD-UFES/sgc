<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PoleController;
use App\Http\Controllers\BondController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\ApprovedController;

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

Route::get('/', [WebController::class, 'rootFork'])->name('root');
Route::fallback([WebController::class, 'fallback']);

Route::get('/login', [\App\Http\Controllers\LoginController::class, 'getLoginForm'])->name('auth.form');
Route::post('/login', [\App\Http\Controllers\LoginController::class, 'authenticate'])->name('auth.login');
Route::get('/logout', [\App\Http\Controllers\LoginController::class, 'logout'])->name('auth.logout');

Route::middleware('auth')->group(function () {
    Route::get('/webhome', [WebController::class, 'webHome'])->name('home');
    Route::get('/webemployee', [WebController::class, 'webEmployee'])->name('employee');
    Route::get('/webfunding', [WebController::class, 'webFunding'])->name('funding');
    Route::get('/webreport', [WebController::class, 'webReport'])->name('report');
    Route::get('/websystem', [WebController::class, 'webSystem'])->name('system');

    Route::resource('employees', EmployeeController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('poles', PoleController::class);
    Route::resource('bonds', BondController::class);
    Route::post('/changeBond', [UserController::class, 'setCurrentBond'])->name('currentBond.change');
    Route::resource('users', UserController::class);
    Route::resource('courses', CourseController::class);
    Route::get('/coursetypes/index', [\App\Http\Controllers\CourseTypeController::class, 'index'])->name('coursetypes.index');
    Route::resource('approveds', ApprovedController::class);
    Route::post('/approved/import', [ApprovedController::class, 'import'])->name('approveds.import');
    Route::get('/approved/review', [ApprovedController::class, 'review'])->name('approveds.review');
    Route::post('/approved/massstore', [ApprovedController::class, 'massStore'])->name('approveds.massstore');
    Route::get('/approved/changestate/{approved}/{state}', [ApprovedController::class, 'changeState'])->name('approveds.changestate');
    Route::post('/approveddesignate', [ApprovedController::class, 'designate'])->name('approveds.designate');
});
