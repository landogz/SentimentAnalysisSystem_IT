<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

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

// Public routes
Route::get('/', function () {
    return redirect()->route('survey.index');
});

// Authentication view routes
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
    
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');
});

// Public survey routes (no authentication required)
Route::prefix('survey')->name('survey.')->group(function () {
    Route::get('/', [SurveyController::class, 'index'])->name('index');
    Route::post('/store', [SurveyController::class, 'store'])->name('store');
    Route::get('/results', [SurveyController::class, 'results'])->name('results');
    Route::post('/validate', [SurveyController::class, 'validateForm'])->name('validate');
    Route::get('/subjects-by-teacher', [SurveyController::class, 'getSubjectsByTeacher'])->name('subjects-by-teacher');
});

// Authentication routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');
    
    // Teacher management
    Route::resource('teachers', TeacherController::class);
    Route::get('/teachers-ajax', [TeacherController::class, 'getTeachers'])->name('teachers.ajax');
    
    // Subject management
    Route::resource('subjects', SubjectController::class);
    Route::get('/subjects-ajax', [SubjectController::class, 'getSubjects'])->name('subjects.ajax');
    
    // Reports
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::post('/reports/export', [ReportsController::class, 'export'])->name('reports.export');
});

// Fallback route
Route::fallback(function () {
    return redirect()->route('survey.index');
});

require __DIR__.'/auth.php';
