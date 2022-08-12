<?php

use App\Http\Controllers\ApprovedController;
use App\Http\Controllers\BondController;
use App\Http\Controllers\BondDocumentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseTypeController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeDocumentController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PoleController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserTypeAssignmentController;
use App\Http\Controllers\WebController;
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

Route::get('/', [WebController::class, 'rootFork'])->name('root');
// *** Don't use the fallback route. Let the user know that the page doesn't exist and log the error.
//Route::fallback([WebController::class, 'fallback']);

Route::get('/login', [LoginController::class, 'getLoginForm'])->name('auth.form');
Route::post('/login', [LoginController::class, 'authenticate'])->name('auth.login');
Route::get('/logout', [LoginController::class, 'logout'])->name('auth.logout');

Route::middleware('auth')->group(function () {
    Route::get('/home', [WebController::class, 'home'])->name('home');

    Route::get('employees/documents', [EmployeeDocumentController::class, 'employeesDocumentsIndex'])->name('employeesDocuments.index');
    //single employee doc create
    Route::get('employees/documents/create', [EmployeeDocumentController::class, 'employeesDocumentsCreate'])->name('employeesDocuments.create');
    Route::post('employees/documents', [EmployeeDocumentController::class, 'employeesDocumentsStore'])->name('employeesDocuments.store');
    //many employee doc create
    Route::get('employees/documents/create-many/step-1/{id?}', [EmployeeDocumentController::class, 'employeesDocumentsCreateMany'])->name('employeesDocuments.createMany');
    Route::post('employees/documents/create-many/step-2', [EmployeeDocumentController::class, 'employeesDocumentsStoreMany1'])->name('employeesDocuments.storeMany1');
    Route::post('employees/documents/create-many/step-3', [EmployeeDocumentController::class, 'employeesDocumentsStoreMany2'])->name('employeesDocuments.storeMany2');
    //mass download
    Route::get('employees/{employee}/documents-export/', [EmployeeDocumentController::class, 'employeesDocumentsExport'])->name('employeesDocuments.export');

    Route::resource('employees', EmployeeController::class);

    Route::get('bonds/documents', [BondDocumentController::class, 'bondsDocumentsIndex'])->name('bondsDocuments.index');
    //single bond doc create
    Route::get('bonds/documents/create', [BondDocumentController::class, 'bondsDocumentsCreate'])->name('bondsDocuments.create');
    Route::post('bonds/documents', [BondDocumentController::class, 'bondsDocumentsStore'])->name('bondsDocuments.store');
    // many bond doc create
    Route::get('bonds/documents/create-many/step-1', [BondDocumentController::class, 'bondsDocumentsCreateMany'])->name('bondsDocuments.createMany');
    Route::post('bonds/documents/create-many/step-2', [BondDocumentController::class, 'bondsDocumentsStoreMany1'])->name('bondsDocuments.storeMany1');
    Route::post('bonds/documents/create-many/step-3', [BondDocumentController::class, 'bondsDocumentsStoreMany2'])->name('bondsDocuments.storeMany2');

    /* Route::resource('documents', DocumentController::class); */

    Route::get('/documents/{id}/{htmlTitle}', [DocumentController::class, 'showDocument'])->name('documents.show');

    //mass download
    Route::get('bonds/{bond}/documents-export', [BondDocumentController::class, 'bondsDocumentsExport'])->name('bondsDocuments.export');

    Route::get('reports/rights', [BondDocumentController::class, 'rightsIndex'])->name('bonds.rights.index');

    Route::resource('bonds', BondController::class);

    Route::post('bondreview/{bond}', [BondController::class, 'review'])->name('bonds.review');
    Route::get('bondrequestreview/{bond}', [BondController::class, 'requestReview'])->name('bonds.requestReview');

    Route::get('users/current/password', [UserController::class, 'currentPasswordEdit'])->name('users.currentPasswordEdit');
    Route::patch('users/current/password', [UserController::class, 'currentPasswordUpdate'])->name('users.currentPasswordUpdate');
    Route::resource('users', UserController::class);

    Route::resource('roles', RoleController::class);
    Route::resource('poles', PoleController::class);
    Route::resource('courses', CourseController::class);

    Route::get('/coursetypes', [CourseTypeController::class, 'index'])->name('coursetypes.index');

    Route::get('/approveds/{approved}/designate', [ApprovedController::class, 'designate'])->name('approveds.designate');

    Route::get('/approveds/create-many/step-1', [ApprovedController::class, 'createManyStep1'])->name('approveds.createMany.step1');
    Route::post('/approveds/create-many/step-1', [ApprovedController::class, 'storeManyStep1'])->name('approveds.storeMany.step1');
    Route::get('/approveds/create-many/step-2', [ApprovedController::class, 'createManyStep2'])->name('approveds.createMany.step2');
    Route::post('/approveds/create-many/step-2', [ApprovedController::class, 'storeManyStep2'])->name('approveds.storeMany.step2');
    Route::get('/approveds', [ApprovedController::class, 'index'])->name('approveds.index');
    Route::get('/approveds/create', [ApprovedController::class, 'create'])->name('approveds.create');
    Route::post('/approveds', [ApprovedController::class, 'store'])->name('approveds.store');
    Route::patch('/approveds/{approved}', [ApprovedController::class, 'update'])->name('approveds.update');
    Route::delete('/approveds/{approved}', [ApprovedController::class, 'destroy'])->name('approveds.destroy');
    //Route::resource('approveds', ApprovedController::class);

    Route::get('/notification/{notification}/dismiss', [NotificationController::class, 'dismiss'])->name('notifications.dismiss');

    Route::resource('userTypeAssignments', UserTypeAssignmentController::class);
    Route::post('/session/changeCurrentUTA', [UserController::class, 'setCurrentUTA'])->name('currentUTA.change');

    Route::get('system/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('logs')->middleware('can:isAdm-global');

    Route::get('system/info', [WebController::class, 'showSysInfo'])->name('sysinfo');
});
