<?php

use App\Http\Controllers\ApprovedBatchController;
use App\Http\Controllers\ApprovedController;
use App\Http\Controllers\BondController;
use App\Http\Controllers\BondDocumentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseTypeController;
use App\Http\Controllers\DesignateApproved;
use App\Http\Controllers\DestroyUserEmployeeLink;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeDocumentController;
use App\Http\Controllers\InstitutionalDetailController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PoleController;
use App\Http\Controllers\RequestBondReview;
use App\Http\Controllers\ReviewBond;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UpdateCurrentPassword;
use App\Http\Controllers\User2Controller;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserTypeAssignmentController;
use App\Http\Controllers\WebController;
use App\Models\Employee;
use App\Models\User;
use App\Models\UserType;
use App\Models\UserTypeAssignment;
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

Route::get('/login', [AuthController::class, 'getLoginForm'])->name('auth.form');
Route::post('/login', [AuthController::class, 'authenticate'])->name('auth.login');
Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');

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
    
    Route::post('employees/{employee}/institutional-details', [InstitutionalDetailController::class, 'store'])->name('employees.institutionalDetails.store');
    Route::get('employees/{employee}/institutional-details/edit', [InstitutionalDetailController::class, 'edit'])->name('employees.institutionalDetails.edit');
    Route::patch('employees/{employee}/institutional-details', [InstitutionalDetailController::class, 'update'])->name('employees.institutionalDetails.update');
    Route::delete('employees/{employee}/institutional-details', [InstitutionalDetailController::class, 'destroy'])->name('employees.institutionalDetails.destroy');

    Route::resource('employees', EmployeeController::class);

    Route::get('bonds/documents', [BondDocumentController::class, 'bondsDocumentsIndex'])->name('bondsDocuments.index');
    //single bond doc create
    Route::get('bonds/documents/create', [BondDocumentController::class, 'bondsDocumentsCreate'])->name('bondsDocuments.create');
    Route::post('bonds/documents', [BondDocumentController::class, 'bondsDocumentsStore'])->name('bondsDocuments.store');
    // many bond doc create
    Route::get('bonds/documents/create-many/step-1', [BondDocumentController::class, 'bondsDocumentsCreateMany'])->name('bondsDocuments.createMany');
    Route::post('bonds/documents/create-many/step-2', [BondDocumentController::class, 'bondsDocumentsStoreMany1'])->name('bondsDocuments.storeMany1');
    Route::post('bonds/documents/create-many/step-3', [BondDocumentController::class, 'bondsDocumentsStoreMany2'])->name('bondsDocuments.storeMany2');

    Route::get('bonds/{bond}/institutional-details/send-email', [InstitutionalDetailController::class, 'sendNewEmployeeEmails'])->name('bonds.sendInstitutionalDetailEmail');

    /* Route::resource('documents', DocumentController::class); */

    Route::get('/documents/{id}/{htmlTitle}', [DocumentController::class, 'showDocument'])->name('documents.show');

    //mass download
    Route::get('bonds/{bond}/documents-export', [BondDocumentController::class, 'bondsDocumentsExport'])->name('bondsDocuments.export');

    Route::get('reports/rights', [BondDocumentController::class, 'rightsIndex'])->name('bonds.rights.index');

    Route::resource('bonds', BondController::class);

    Route::post('bondreview/{bond}', ReviewBond::class)->name('bonds.review');
    Route::get('bondreviewrequest/{bond}', RequestBondReview::class)->name('bonds.requestReview');

    Route::get('users/current-password', [AuthController::class, 'currentPasswordEdit'])->name('users.currentPasswordEdit');
    Route::patch('users/current-password', UpdateCurrentPassword::class)->name('users.currentPasswordUpdate');

    Route::delete('users/{user}/destroy-employee-link', DestroyUserEmployeeLink::class)->name('users.destroyEmployeeLink');

    Route::resource('users', UserController::class);

    Route::resource('roles', RoleController::class);
    Route::resource('poles', PoleController::class);
    Route::resource('courses', CourseController::class);

    Route::get('/coursetypes', [CourseTypeController::class, 'index'])->name('coursetypes.index');

    Route::get('/approveds/{approved}/designate', DesignateApproved::class)->name('approveds.designate');

    Route::get('/approveds/create-many/step-1', [ApprovedBatchController::class, 'createManyStep1'])->name('approveds.createMany.step1');
    Route::post('/approveds/create-many/step-1', [ApprovedBatchController::class, 'storeManyStep1'])->name('approveds.storeMany.step1');
    Route::get('/approveds/create-many/step-2', [ApprovedBatchController::class, 'createManyStep2'])->name('approveds.createMany.step2');
    Route::post('/approveds/create-many/step-2', [ApprovedBatchController::class, 'storeManyStep2'])->name('approveds.storeMany.step2');
    
    Route::get('/approveds', [ApprovedController::class, 'index'])->name('approveds.index');
    Route::get('/approveds/create', [ApprovedController::class, 'create'])->name('approveds.create');
    Route::post('/approveds', [ApprovedController::class, 'store'])->name('approveds.store');
    Route::patch('/approveds/{approved}', [ApprovedController::class, 'update'])->name('approveds.update');
    Route::delete('/approveds/{approved}', [ApprovedController::class, 'destroy'])->name('approveds.destroy');
    //Route::resource('approveds', ApprovedController::class);

    Route::get('/notification/{notification}/dismiss', [NotificationController::class, 'dismiss'])->name('notifications.dismiss');

    Route::resource('userTypeAssignments', UserTypeAssignmentController::class);
    Route::post('/session/switch-responsibility', [AuthController::class, 'switchCurrentResponsibility'])->name('currentUTA.change');

    Route::get('system/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('logs')->middleware('can:isAdm-global');

    Route::get('system/info', [WebController::class, 'showSysInfo'])->name('sysinfo');
});
