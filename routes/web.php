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
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CourseTypeController;

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

Route::get('/login', [LoginController::class, 'getLoginForm'])->name('auth.form');
Route::post('/login', [LoginController::class, 'authenticate'])->name('auth.login');
Route::get('/logout', [LoginController::class, 'logout'])->name('auth.logout');

Route::middleware('auth')->group(function () {
    Route::get('/webhome', [WebController::class, 'webHome'])->name('home');

    Route::resource('employees', EmployeeController::class);

    Route::resource('bonds', BondController::class);
    Route::post('bondreview/{bond}', [BondController::class, 'review'])->name('bonds.review');
    Route::get('bondrequestreview/{bond}', [BondController::class, 'requestReview'])->name('bonds.requestReview');

    /* Route::resource('documents', DocumentController::class); */

    Route::get('employeesdocumentindex', [DocumentController::class, 'employeesDocumentIndex'])->name('employees.document.index');
    Route::get('employeesdocumentcreate', [DocumentController::class, 'employeesDocumentCreate'])->name('employees.document.create');
    Route::get('employeesdocumentcreate/{id}', [DocumentController::class, 'employeesDocumentCreate'])->name('employees.document.create.id');
    //Route::post('employeesdocumentstore', [DocumentController::class, 'employeesDocumentStore'])->name('employees.document.store');
    Route::post('employeesdocumentmassimport', [DocumentController::class, 'employeesDocumentMassImport'])->name('employees.document.mass.import');
    Route::post('employeesdocumentmassstore', [DocumentController::class, 'employeesDocumentMassStore'])->name('employees.document.mass.store');

    Route::get('bondsdocumentindex', [DocumentController::class, 'bondsDocumentIndex'])->name('bonds.document.index');
    Route::get('bondsdocumentcreate', [DocumentController::class, 'bondsDocumentCreate'])->name('bonds.document.create');
    Route::post('bondsdocumentstore', [DocumentController::class, 'bondsDocumentStore'])->name('bonds.document.store');
    Route::get('bonddocumentsmassdownload/{bond}', [DocumentController::class, 'bondDocumentsMassDownload'])->name('bonds.document.massdownload');
    Route::get('rights', [DocumentController::class, 'rightsIndex'])->name('bonds.rights.index');

    Route::get('/document/{id}/{type}/{htmlTitle}', [DocumentController::class, 'showDocument'])->name('documents.show');

    Route::resource('users', UserController::class);
    Route::post('/changeBond', [UserController::class, 'setCurrentBond'])->name('currentBond.change');

    Route::resource('roles', RoleController::class);
    Route::resource('poles', PoleController::class);
    Route::resource('courses', CourseController::class);
    
    Route::get('/coursetypes/index', [CourseTypeController::class, 'index'])->name('coursetypes.index');

    Route::resource('approveds', ApprovedController::class);
    Route::post('/approved/import', [ApprovedController::class, 'import'])->name('approveds.import');
    Route::post('/approved/massstore', [ApprovedController::class, 'massStore'])->name('approveds.massstore');
    Route::get('/approved/changestate/{approved}/{state}', [ApprovedController::class, 'changeState'])->name('approveds.changestate');
    Route::post('/approveddesignate', [ApprovedController::class, 'designate'])->name('approveds.designate');
});
