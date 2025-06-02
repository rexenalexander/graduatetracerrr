<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GraduatesController;  // Changed from GraduateController
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmployersController;
use App\Http\Controllers\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Guest routes
Route::get('/', function () {
    return view('auth.login');
})->name('home');

Route::get('/login', function () {
    return view('auth.login', ['isAdmin' => true]);
})->name('login');

Route::get('/check-log', function () {
    return nl2br(file_get_contents(storage_path('logs/laravel.log')));
});

Route::get('/forgot-pass', [LoginController::class, 'index'])->name('forgot-pass');
Route::get('/change-pass', [LoginController::class, 'changepass'])->name('change-pass');

// Member routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Graduate routes with full CRUD - Updated controller name
    Route::get('/graduates', [GraduatesController::class, 'index'])->name('graduates.index');
    Route::get('/graduates/create', [GraduatesController::class, 'create'])->name('graduates.create');
    Route::post('/graduates', [GraduatesController::class, 'store'])->name('graduates.store');
    Route::get('/graduates/{graduate}', [GraduatesController::class, 'show'])->name('graduates.show');
    Route::get('/graduates/{graduate}/edit', [GraduatesController::class, 'edit'])->name('graduates.edit');
    Route::put('/graduates/{graduate}', [GraduatesController::class, 'update'])->name('graduates.update');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/employers/create', [EmployersController::class, 'create'])->name('employers.create');
    Route::post('/employers', [EmployersController::class, 'store'])->name('employers.store');
    Route::get('/employers/{employer}/edit', [EmployersController::class, 'edit'])->name('employers.edit');
    Route::put('/employers/{employer}', [EmployersController::class, 'update'])->name('employers.update');
    Route::get('/mysurveys', [EmployersController::class, 'mysurveys'])->name('survey.employers');
    Route::get('/survey-details/{employer}', [EmployersController::class, 'getEmployerSurveyData'])->name('survey.survey-details');

});
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/graduates', [AdminController::class, 'graduates'])->name('admin.graduates');
    Route::post('/graduates/notify', [AdminController::class, 'notify'])->name('admin.graduates.notify');
    Route::post('/graduates/notifygraduate', [AdminController::class, 'notifygraduate'])->name('admin.graduates.notifygraduate');
    Route::get('/notifypage', [AdminController::class, 'notifypage'])->name('admin.notifypage');
    Route::get('/employernotifypage', [AdminController::class, 'employernotifypage'])->name('admin.employernotifypage');

    Route::get('/employers', [AdminController::class, 'employers'])->name('admin.employers');
    Route::get('/history/{graduate}', [AdminController::class, 'history'])->name('admin.history');
    Route::get('/clearjobs', [AdminController::class, 'clearjobs'])->name('admin.clearjobs');

    Route::get('/employer-survey/{employer}', [AdminController::class, 'getEmployerSurveyData'])->name('admin.employer-survey');


    Route::get('/graduates/{graduate}/edit', [AdminController::class, 'edit'])->name('admin.graduates.edit');
    Route::put('/graduates/{graduate}', [AdminController::class, 'update'])->name('admin.graduates.update');
    Route::delete('/graduates/{graduate}', [AdminController::class, 'destroy'])->name('admin.graduates.destroy');
    Route::get('/export', [AdminController::class, 'exportCsv'])->name('admin.export');
    Route::get('/admin/employment-stats/{year}', [AdminController::class, 'employmentStats'])
        ->name('admin.employment-stats')
        ->middleware(['auth', 'admin']);
    Route::get('/graduate-survey/{graduate}', [AdminController::class, 'getSurveyData'])->name('admin.graduate-survey');

    Route::get('/importpage', [AdminController::class, 'importpage'])->name('admin.importpage');
    Route::post('/importpage/importlist', [AdminController::class, 'importlist'])->name('admin.graduates.importlist');


});

require __DIR__.'/auth.php';
