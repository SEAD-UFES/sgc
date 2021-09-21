<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PoleController;
use App\Http\Controllers\BondController;
use App\Http\Controllers\BondDocumentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeDocumentController;
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

    Route::resource('employees', EmployeeController::class);
    Route::resource('bonds', BondController::class);

    Route::post('bondreview/{bond}', [BondController::class, 'review'])->name('bonds.review');
    Route::get('bondrequestreview/{bond}', [BondController::class, 'requestReview'])->name('bonds.requestReview');

    /* Route::resource('documents', DocumentController::class); */

    Route::get('employeesdocumentindex', [DocumentController::class, 'employeesDocumentIndex'])->name('employees.document.index');
    //single employee doc create
    Route::get('employeedocuments/create', [EmployeeDocumentController::class, 'create'])->name('employeeDocuments.create');
    Route::post('employeedocuments', [EmployeeDocumentController::class, 'store'])->name('employeeDocuments.store');
    //many employee doc create
    Route::get('employeedocuments/create-many/p1', [DocumentController::class, 'employeeDocumentCreateMany'])->name('employees.document.create');
    Route::post('employeedocuments/create-many/p2', [DocumentController::class, 'employeeDocumentStoreManyFase1'])->name('employees.document.mass.import');
    Route::post('employeedocuments/create-many/p3', [DocumentController::class, 'employeeDocumentStoreManyFase2'])->name('employees.document.mass.store');
    //mass download
    Route::get('employeedocumentsmassdownload/{employee}', [DocumentController::class, 'employeeDocumentsMassDownload'])->name('employees.document.massdownload');

    Route::get('bondsdocumentindex', [DocumentController::class, 'bondsDocumentIndex'])->name('bonds.document.index');
    //single bond doc create
    Route::get('bonddocuments/create', [DocumentController::class, 'bondsDocumentCreate'])->name('bonds.document.create');
    Route::post('bonddocuments', [DocumentController::class, 'bondsDocumentStore'])->name('bonds.document.store');
    // many bond doc create
    Route::get('bonddocuments/create-many/p1', [BondDocumentController::class, 'createMany'])->name('bondDocuments.createMany');
    Route::post('bonddocuments/create-many/p2', [BondDocumentController::class, 'storeManyFase1'])->name('bondDocuments.storeManyFase01');
    Route::post('bonddocuments/create-many/p3', [BondDocumentController::class, 'storeManyFase2'])->name('bondDocuments.storeManyFase02');
    //mass download
    Route::get('bonddocumentsmassdownload/{bond}', [DocumentController::class, 'bondDocumentsMassDownload'])->name('bonds.document.massdownload');
    Route::get('rights', [DocumentController::class, 'rightsIndex'])->name('bonds.rights.index');

    Route::get('/document/{id}/{type}/{htmlTitle}', [DocumentController::class, 'showDocument'])->name('documents.show');

    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('poles', PoleController::class);
    Route::resource('courses', CourseController::class);

    Route::get('/coursetypes/index', [CourseTypeController::class, 'index'])->name('coursetypes.index');

    Route::resource('approveds', ApprovedController::class);
    Route::post('/approved/import', [ApprovedController::class, 'import'])->name('approveds.import');
    Route::post('/approved/massstore', [ApprovedController::class, 'massStore'])->name('approveds.massstore');
    Route::post('/approved/changestate/{approved}', [ApprovedController::class, 'changeState'])->name('approveds.changestate');
    Route::post('/approveddesignate', [ApprovedController::class, 'designate'])->name('approveds.designate');

    Route::get('/notification/{notification}/dismiss', [NotificationController::class, 'dismiss'])->name('notifications.dismiss');

    Route::resource('userTypeAssignments', UserTypeAssignmentController::class);
    Route::post('/session/changeCurrentUTA', [UserController::class, 'setCurrentUTA'])->name('currentUTA.change');

    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('logs');
});
