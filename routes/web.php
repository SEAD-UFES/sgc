<?php

use App\Http\Controllers\ApprovedBatchController;
use App\Http\Controllers\ApprovedController;
use App\Http\Controllers\BondController;
use App\Http\Controllers\BondDocumentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseTypeController;
use App\Http\Controllers\DesignateApprovedController;
use App\Http\Controllers\DestroyUserEmployeeLinkController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeDocumentController;
use App\Http\Controllers\InstitutionalDetailController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BondDocumentBatchController;
use App\Http\Controllers\EmployeeDocumentBatchController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PoleController;
use App\Http\Controllers\RequestBondReviewController;
use App\Http\Controllers\ReviewBondController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UpdateCurrentPasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ResponsibilityController;
use App\Http\Controllers\RightsDocumentController;
use App\Http\Controllers\SendNewEmployeeEmailsController;
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

Route::get('/login', [AuthController::class, 'getLoginForm'])->name('auth.form');
Route::post('/login', [AuthController::class, 'authenticate'])->name('auth.login');
Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::middleware('auth')->group(function () {
    Route::get('/home', [WebController::class, 'home'])->name('home');

    Route::get('/employees/documents', [EmployeeDocumentController::class, 'index'])->name('employeesDocuments.index');
    //single employee doc create
    Route::get('/employees/documents/create', [EmployeeDocumentController::class, 'create'])->name('employeesDocuments.create');
    Route::post('/employees/documents', [EmployeeDocumentController::class, 'store'])->name('employeesDocuments.store');
    //many employee doc create
    Route::get('/employees/documents/create-many/step-1/{id?}', [EmployeeDocumentBatchController::class, 'create'])->name('employeesDocuments.createMany');
    Route::post('/employees/documents/create-many/step-2', [EmployeeDocumentBatchController::class, 'store'])->name('employeesDocuments.storeMany1');
    Route::post('/employees/documents/create-many/step-3', [EmployeeDocumentBatchController::class, 'store2'])->name('employeesDocuments.storeMany2');
    //mass download
    Route::get('/employees/{employee}/documents-export/', [EmployeeDocumentController::class, 'export'])->name('employeesDocuments.export');
    
    Route::post('/employees/{employee}/institutional-details', [InstitutionalDetailController::class, 'store'])->name('employees.institutionalDetails.store');
    Route::get('/employees/{employee}/institutional-details/edit', [InstitutionalDetailController::class, 'edit'])->name('employees.institutionalDetails.edit');
    Route::patch('/employees/{employee}/institutional-details', [InstitutionalDetailController::class, 'update'])->name('employees.institutionalDetails.update');
    Route::delete('/employees/{employee}/institutional-details', [InstitutionalDetailController::class, 'destroy'])->name('employees.institutionalDetails.destroy');

    Route::resource('employees', EmployeeController::class);

    Route::get('/bonds/documents', [BondDocumentController::class, 'index'])->name('bondsDocuments.index');
    //single bond doc create
    Route::get('/bonds/documents/create', [BondDocumentController::class, 'create'])->name('bondsDocuments.create');
    Route::post('/bonds/documents', [BondDocumentController::class, 'store'])->name('bondsDocuments.store');
    // many bond doc create
    Route::get('/bonds/documents/create-many/step-1', [BondDocumentBatchController::class, 'create'])->name('bondsDocuments.createMany');
    Route::post('/bonds/documents/create-many/step-2', [BondDocumentBatchController::class, 'store'])->name('bondsDocuments.storeMany1');
    Route::post('/bonds/documents/create-many/step-3', [BondDocumentBatchController::class, 'store2'])->name('bondsDocuments.storeMany2');

    Route::get('/bonds/{bond}/institutional-details/send-email', SendNewEmployeeEmailsController::class)->name('bonds.sendInstitutionalDetailEmail');

    /* Route::resource('documents', DocumentController::class); */

    Route::get('/employees/documents/{id}/{htmlTitle}', [EmployeeDocumentController::class, 'show'])->name('employeesDocuments.show');
    Route::get('/bonds/documents/{id}/{htmlTitle}', [BondDocumentController::class, 'show'])->name('bondsDocuments.show');
    
    Route::get('/reports/rights', [RightsDocumentController::class, 'index'])->name('bonds.rights.index');
    Route::get('/bonds/rights/{id}/{htmlTitle}', [RightsDocumentController::class, 'show'])->name('bonds.rights.show');
    //mass download
    Route::get('/bonds/{bond}/documents-export', [BondDocumentController::class, 'export'])->name('bondsDocuments.export');


    Route::resource('bonds', BondController::class);

    Route::post('/bondreview/{bond}', ReviewBondController::class)->name('bonds.review');
    Route::get('/bondreviewrequest/{bond}', RequestBondReviewController::class)->name('bonds.requestReview');

    Route::get('/users/current-password', [AuthController::class, 'currentPasswordEdit'])->name('users.currentPasswordEdit');
    Route::patch('/users/current-password', UpdateCurrentPasswordController::class)->name('users.currentPasswordUpdate');

    Route::delete('/users/{user}/destroy-employee-link', DestroyUserEmployeeLinkController::class)->name('users.destroyEmployeeLink');

    Route::resource('users', UserController::class);

    Route::resource('roles', RoleController::class);
    Route::resource('poles', PoleController::class);
    Route::resource('courses', CourseController::class);

    Route::get('/coursetypes', [CourseTypeController::class, 'index'])->name('coursetypes.index');

    Route::get('/approveds/{approved}/designate', DesignateApprovedController::class)->name('approveds.designate');

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

    Route::resource('responsibilities', ResponsibilityController::class);
    Route::post('/session/switch-responsibility', [AuthController::class, 'switchCurrentResponsibility'])->name('currentResponsibility.change');

    Route::get('/system/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('logs')->middleware('can:isAdm-global');

    Route::get('/system/info', [WebController::class, 'showSysInfo'])->name('sysinfo');
});
