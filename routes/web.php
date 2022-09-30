<?php

use App\Http\Controllers\ApprovedBatchController;
use App\Http\Controllers\ApprovedController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BondController;
use App\Http\Controllers\BondDocumentBatchController;
use App\Http\Controllers\BondDocumentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseTypeController;
use App\Http\Controllers\DesignateApprovedController;
use App\Http\Controllers\DestroyUserEmployeeLinkController;
use App\Http\Controllers\DismissNotificationController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeDocumentBatchController;
use App\Http\Controllers\EmployeeDocumentController;
use App\Http\Controllers\DownloadBondDocumentsPackController;
use App\Http\Controllers\DownloadEmployeeDocumentsPackController;
use App\Http\Controllers\InstitutionalDetailController;
use App\Http\Controllers\PoleController;
use App\Http\Controllers\RequestBondReviewController;
use App\Http\Controllers\ResponsibilityController;
use App\Http\Controllers\ReviewBondController;
use App\Http\Controllers\RightsDocumentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SendNewEmployeeEmailsController;
use App\Http\Controllers\UpdateCurrentPasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;

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

Route::controller(AuthController::class)->group(function () {
    Route::get('login', 'getLoginForm')->name('auth.form');
    Route::post('login', 'authenticate')->name('auth.login');
    Route::get('logout', 'logout')->name('auth.logout');

    Route::middleware('auth')->group(function () {
        Route::get('users/current/password', 'currentPasswordEdit')->name('users.current_password_edit');
        Route::post('users/current/switch-responsibility', 'switchCurrentResponsibility')->name('users.responsibility_switch');
    });
});

Route::middleware('auth')->group(function () {
    Route::controller(ApprovedBatchController::class)->group(function () {
        Route::get('approveds/create-many/step-1', 'createManyStep1')->name('approveds.create_many.step_1');
        Route::post('approveds/create-many/step-1', 'storeManyStep1')->name('approveds.store_many.step_1');
        Route::get('approveds/create-many/step-2', 'createManyStep2')->name('approveds.create_many.step_2');
        Route::post('approveds/create-many/step-2', 'storeManyStep2')->name('approveds.store_many.step_2');
    });

    Route::controller(ApprovedController::class)->group(function () {
        Route::get('approveds', 'index')->name('approveds.index');
        Route::get('approveds/create', 'create')->name('approveds.create');
        Route::post('approveds', 'store')->name('approveds.store');
        Route::patch('approveds/{approved}', 'update')->name('approveds.update');
        Route::delete('approveds/{approved}', 'destroy')->name('approveds.destroy');
    });
    Route::controller(BondDocumentBatchController::class)->group(function () {
        Route::get('bonds/documents/create-many/step-1', 'create')->name('bonds_documents.create_many');
        Route::post('bonds/documents/create-many/step-2', 'store')->name('bonds_documents.store_many_1');
        Route::post('bonds/documents/create-many/step-3', 'store2')->name('bonds_documents.store_many_2');
    });

    Route::controller(BondDocumentController::class)->group(function () {
        Route::get('bonds/documents', 'index')->name('bonds_documents.index');
        Route::get('bonds/documents/create', 'create')->name('bonds_documents.create');
        Route::post('bonds/documents', 'store')->name('bonds_documents.store');
        Route::get('bonds/documents/{id}/{htmlTitle}', 'show')->name('bonds_documents.show');
    });

    Route::controller(EmployeeDocumentBatchController::class)->group(function () {
        Route::get('employees/documents/create-many/step-1/{id?}', 'create')->name('employees_documents.create_many');
        Route::post('employees/documents/create-many/step-2', 'store')->name('employees_documents.store_many_1');
        Route::post('employees/documents/create-many/step-3', 'store2')->name('employees_documents.store_many_2');
    });

    Route::controller(EmployeeDocumentController::class)->group(function () {
        Route::get('employees/documents', 'index')->name('employees_documents.index');
        Route::get('employees/documents/create', 'create')->name('employees_documents.create');
        Route::post('employees/documents', 'store')->name('employees_documents.store');
        Route::get('employees/documents/{id}/{htmlTitle}', 'show')->name('employees_documents.show');
    });

    Route::controller(InstitutionalDetailController::class)->group(function () {
        Route::post('employees/{employee}/institutional-details', 'store')->name('employees.institutional_details.store');
        Route::get('employees/{employee}/institutional-details/edit', 'edit')->name('employees.institutional_details.edit');
        Route::patch('employees/{employee}/institutional-details', 'update')->name('employees.institutional_details.update');
        Route::delete('employees/{employee}/institutional-details', 'destroy')->name('employees.institutional_details.destroy');
    });

    Route::controller(WebController::class)->group(function () {
        Route::get('home', 'home')->name('home');
        Route::get('system/info', 'showSysInfo')->name('system_info');
    });

    Route::get('system/logs', [LogViewerController::class, 'index'])->name('system_logs.index')->middleware('can:isAdm-global');

    Route::controller(RightsDocumentController::class)->group(function () {
        Route::get('reports/rights', 'index')->name('rights.index');
        Route::get('bonds/rights/{id}/{htmlTitle}', 'show')->name('rights.show');
    });

    // Single Action Controllers
    Route::get('approveds/{approved}/designate', DesignateApprovedController::class)->name('approveds.designate');
    Route::get('bonds/{bond}/institutional-details/send-email', SendNewEmployeeEmailsController::class)->name('bonds.send_new_employee_emails');
    Route::post('bonds/{bond}/review', ReviewBondController::class)->name('bonds.review');
    Route::get('bonds/{bond}/review-request', RequestBondReviewController::class)->name('bonds.request_review');
    Route::get('notifications/{notification}/dismiss', DismissNotificationController::class)->name('notifications.dismiss');
    Route::patch('users/current/password', UpdateCurrentPasswordController::class)->name('users.current_password_update');
    Route::delete('users/{user}/destroy-employee-link', DestroyUserEmployeeLinkController::class)->name('users.destroy_employee_link');
    Route::get('bonds/{bond}/documents-export', DownloadBondDocumentsPackController::class)->name('bonds_documents.export');
    Route::get('employees/{employee}/documents-export/', DownloadEmployeeDocumentsPackController::class)->name('employees_documents.export');

    Route::get('coursetypes', [CourseTypeController::class, 'index'])->name('coursetypes.index');

    // Resource Controllers
    Route::resource('bonds', BondController::class);
    Route::resource('courses', CourseController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('poles', PoleController::class)
        ->except([
            'show'
        ]);
    Route::resource('responsibilities', ResponsibilityController::class);
    Route::resource('roles', RoleController::class)
        ->except([
            'show'
        ]);
    Route::resource('users', UserController::class);
});
