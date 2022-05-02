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
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserTypeAssignmentController;

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


    Route::get('employees/documents', [DocumentController::class, 'employeesDocumentsIndex'])->name('employeesDocuments.index');
    //single employee doc create
    Route::get('employees/documents/create', [DocumentController::class, 'employeesDocumentsCreate'])->name('employeesDocuments.create');
    Route::post('employees/documents', [DocumentController::class, 'employeesDocumentsStore'])->name('employeesDocuments.store');
    //many employee doc create
    Route::get('employees/documents/create-many/step-1/{id?}', [DocumentController::class, 'employeesDocumentsCreateMany'])->name('employeesDocuments.createMany');
    Route::post('employees/documents/create-many/step-2', [DocumentController::class, 'employeesDocumentsStoreMany1'])->name('employeesDocuments.storeMany1');
    Route::post('employees/documents/create-many/step-3', [DocumentController::class, 'employeesDocumentsStoreMany2'])->name('employeesDocuments.storeMany2');
    //mass download
    Route::get('employees/{employee}/documents-export/', [DocumentController::class, 'employeesDocumentsExport'])->name('employeesDocuments.export');

    Route::resource('employees', EmployeeController::class);


    Route::get('bonds/documents', [DocumentController::class, 'bondsDocumentsIndex'])->name('bondsDocuments.index');
    //single bond doc create
    Route::get('bonds/documents/create', [DocumentController::class, 'bondsDocumentsCreate'])->name('bondsDocuments.create');
    Route::post('bonds/documents', [DocumentController::class, 'bondsDocumentsStore'])->name('bondsDocuments.store');
    // many bond doc create
    Route::get('bonds/documents/create-many/step-1', [DocumentController::class, 'bondsDocumentsCreateMany'])->name('bondsDocuments.createMany');
    Route::post('bonds/documents/create-many/step-2', [DocumentController::class, 'bondsDocumentsStoreMany1'])->name('bondsDocuments.storeMany1');
    Route::post('bonds/documents/create-many/step-3', [DocumentController::class, 'bondsDocumentsStoreMany2'])->name('bondsDocuments.storeMany2');
    //mass download
    Route::get('bonds/{bond}/documents-export', [DocumentController::class, 'bondsDocumentsExport'])->name('bondsDocuments.export');

    Route::get('reports/rights', [DocumentController::class, 'rightsIndex'])->name('bonds.rights.index');

    Route::resource('bonds', BondController::class);


    Route::post('bondreview/{bond}', [BondController::class, 'review'])->name('bonds.review');
    Route::get('bondrequestreview/{bond}', [BondController::class, 'requestReview'])->name('bonds.requestReview');

    /* Route::resource('documents', DocumentController::class); */

    Route::get('/documents/{id}/{htmlTitle}', [DocumentController::class, 'showDocument'])->name('documents.show');

    Route::get('users/current/password', [UserController::class, 'currentPasswordEdit'])->name('users.currentPasswordEdit');
    Route::patch('users/current/password', [UserController::class, 'currentPasswordUpdate'])->name('users.currentPasswordUpdate');
    Route::resource('users', UserController::class);

    Route::resource('roles', RoleController::class);
    Route::resource('poles', PoleController::class);
    Route::resource('courses', CourseController::class);

    Route::get('/coursetypes/index', [CourseTypeController::class, 'index'])->name('coursetypes.index');

    Route::resource('approveds', ApprovedController::class);
    Route::post('/approved/import', [ApprovedController::class, 'import'])->name('approveds.import');
    Route::post('/approved/massstore', [ApprovedController::class, 'massStore'])->name('approveds.massstore');
    Route::post('/approved/{approved}/changestate', [ApprovedController::class, 'changeState'])->name('approveds.changestate');
    Route::get('/approved/{approved}/designate', [ApprovedController::class, 'designate'])->name('approveds.designate');

    Route::get('/notification/{notification}/dismiss', [NotificationController::class, 'dismiss'])->name('notifications.dismiss');

    Route::resource('userTypeAssignments', UserTypeAssignmentController::class);
    Route::post('/session/changeCurrentUTA', [UserController::class, 'setCurrentUTA'])->name('currentUTA.change');

    Route::get('system/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('logs')->middleware('can:isAdm-global');

    Route::get('system/info', [WebController::class, 'showSysInfo'])->name('sysinfo');
});
