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

    Route::resource('employees', EmployeeController::class);
    Route::resource('bonds', BondController::class);

    Route::post('bondreview/{bond}', [BondController::class, 'review'])->name('bonds.review');
    Route::get('bondrequestreview/{bond}', [BondController::class, 'requestReview'])->name('bonds.requestReview');

    /* Route::resource('documents', DocumentController::class); */

    Route::get('employeesdocumentindex', [DocumentController::class, 'employeesDocumentsIndex'])->name('employeesDocuments.index');
    //single employee doc create
    Route::get('employeedocuments/create', [DocumentController::class, 'employeesDocumentsCreate'])->name('employeesDocuments.create');
    Route::post('employeedocuments', [DocumentController::class, 'employeesDocumentsStore'])->name('employeesDocuments.store');
    //many employee doc create
    Route::get('employeedocuments/create-many/p1/{id?}', [DocumentController::class, 'employeesDocumentsCreateMany'])->name('employeesDocuments.createMany');
    Route::post('employeedocuments/create-many/p2', [DocumentController::class, 'employeesDocumentsStoreManyStep1'])->name('employeesDocuments.storeManyStep01');
    Route::post('employeedocuments/create-many/p3', [DocumentController::class, 'employeesDocumentsStoreManyStep2'])->name('employeesDocuments.storeManyStep02');
    //mass download
    Route::get('employeedocumentsmassdownload/{employee}', [DocumentController::class, 'employeesDocumentsMassDownload'])->name('employeesDocuments.massdownload');

    Route::get('bondsdocumentindex', [DocumentController::class, 'bondsDocumentsIndex'])->name('bondsDocuments.index');
    //single bond doc create
    Route::get('bonddocuments/create', [DocumentController::class, 'bondsDocumentsCreate'])->name('bondsDocuments.create');
    Route::post('bonddocuments', [DocumentController::class, 'bondsDocumentsStore'])->name('bondsDocuments.store');
    // many bond doc create
    Route::get('bonddocuments/create-many/p1', [DocumentController::class, 'bondsDocumentsCreateMany'])->name('bondsDocuments.createMany');
    Route::post('bonddocuments/create-many/p2', [DocumentController::class, 'bondsDocumentsStoreManyStep1'])->name('bondsDocuments.storeManyStep01');
    Route::post('bonddocuments/create-many/p3', [DocumentController::class, 'bondsDocumentsStoreManyStep2'])->name('bondsDocuments.storeManyStep02');
    //mass download
    Route::get('bonddocumentsmassdownload/{bond}', [DocumentController::class, 'bondsDocumentsMassDownload'])->name('bondsDocuments.massdownload');

    Route::get('rights', [DocumentController::class, 'rightsIndex'])->name('bonds.rights.index');

    Route::get('/document/{id}/{htmlTitle}', [DocumentController::class, 'showDocument'])->name('documents.show');

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

    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('logs')->middleware('can:isAdm-global');
    
    Route::get('sysinfo', [WebController::class, 'showSysInfo'])->name('sysinfo');
});
