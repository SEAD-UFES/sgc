<?php

use App\Http\Controllers\ApplicantBatchController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BondController;
use App\Http\Controllers\CourseClassController;
use App\Http\Controllers\DocumentBatchController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseTypeController;
use App\Http\Controllers\DesignateApplicantController;
use App\Http\Controllers\DestroyUserEmployeeLinkController;
use App\Http\Controllers\DismissNotificationController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DownloadDocumentsPackController;
use App\Http\Controllers\ImpedimentController;
use App\Http\Controllers\InstitutionalDetailController;
use App\Http\Controllers\PoleController;
use App\Http\Controllers\RequestBondReviewController;
use App\Http\Controllers\ResponsibilityController;
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
    Route::controller(ApplicantBatchController::class)->group(function () {
        Route::get('applicants/create-many/step-1', 'createManyStep1')->name('applicants.create_many.step_1');
        Route::post('applicants/create-many/step-1', 'storeManyStep1')->name('applicants.store_many.step_1');
        Route::get('applicants/create-many/step-2', 'createManyStep2')->name('applicants.create_many.step_2');
        Route::post('applicants/create-many/step-2', 'storeManyStep2')->name('applicants.store_many.step_2');
    });

    Route::controller(ApplicantController::class)->group(function () {
        Route::get('applicants', 'index')->name('applicants.index');
        Route::get('applicants/create', 'create')->name('applicants.create');
        Route::post('applicants', 'store')->name('applicants.store');
        Route::patch('applicants/{applicant}', 'update')->name('applicants.update');
        Route::delete('applicants/{applicant}', 'destroy')->name('applicants.destroy');
    });
    Route::controller(DocumentBatchController::class)->group(function () {
        Route::get('documents/create-many/step-1', 'create')->name('documents.create_many');
        Route::post('documents/create-many/step-2', 'store')->name('documents.store_many_1');
        Route::post('documents/create-many/step-3', 'store2')->name('documents.store_many_2');
    });

    Route::controller(DocumentController::class)->group(function () {
        Route::get('documents', 'index')->name('documents.index');
        Route::get('documents/create', 'create')->name('documents.create');
        Route::post('documents', 'store')->name('documents.store');
        Route::get('documents/{id}/{htmlTitle}', 'show')->name('documents.show');
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
    Route::get('applicants/{applicant}/designate', DesignateApplicantController::class)->name('applicants.designate');
    Route::get('bonds/{bond}/institutional-details/send-email', SendNewEmployeeEmailsController::class)->name('bonds.send_new_employee_emails');
    Route::get('bonds/{bond}/review-request', RequestBondReviewController::class)->name('bonds.request_review');
    Route::get('notifications/{notification}/dismiss', DismissNotificationController::class)->name('notifications.dismiss');
    Route::patch('users/current/password', UpdateCurrentPasswordController::class)->name('users.current_password_update');
    Route::delete('users/{user}/destroy-employee-link', DestroyUserEmployeeLinkController::class)->name('users.destroy_employee_link');
    Route::get('bonds/{bond}/documents-export', DownloadDocumentsPackController::class)->name('documents.export');

    Route::get('coursetypes', [CourseTypeController::class, 'index'])->name('coursetypes.index');

    // Resource Controllers
    Route::resource('bonds', BondController::class);
    Route::resource('courses', CourseController::class);
    Route::resource('course-classes', CourseClassController::class);
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

    Route::resource('impediments', ImpedimentController::class)->only([
        'store', 'update'
    ]);
});
