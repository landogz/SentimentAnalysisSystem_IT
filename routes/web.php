<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SurveyQuestionController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SentimentWordController;
use App\Http\Controllers\Auth\StudentAuthController;
use App\Http\Controllers\StudentController;

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
    if (auth('student')->check()) {
        return redirect()->route('survey.index');
    }
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('student.login');
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
    
    Route::get('/reset-password/{token}', function (Request $request, $token) {
        $email = $request->query('email');
        
        // Log for debugging
        \Log::info('Reset password page accessed', [
            'token' => $token,
            'email' => $email,
            'all_query_params' => $request->query()
        ]);
        
        // If email is missing, try to get it from the token
        if (empty($email)) {
            // Try to find the token record - Laravel might store it differently
            $tokenRecord = \DB::table('password_reset_tokens')->where('token', $token)->first();
            if (!$tokenRecord) {
                // Try with hashed token
                $tokenRecord = \DB::table('password_reset_tokens')->where('token', hash('sha256', $token))->first();
            }
            if (!$tokenRecord) {
                // Try with base64 encoded token
                $tokenRecord = \DB::table('password_reset_tokens')->where('token', base64_encode($token))->first();
            }
            
            if ($tokenRecord) {
                $email = $tokenRecord->email;
                \Log::info('Email retrieved from token', ['email' => $email, 'token_type' => 'found']);
            } else {
                \Log::warning('Token not found in database', ['token' => $token]);
            }
        }
        
        return view('auth.reset-password', ['request' => $request, 'token' => $token, 'email' => $email]);
    })->name('password.reset');
});

// Student authentication routes
Route::middleware('guest:student')->group(function () {
    Route::get('/student/login', [StudentAuthController::class, 'showLoginForm'])->name('student.login');
    Route::post('/student/login', [StudentAuthController::class, 'login'])->name('student.login');
    Route::get('/student/register', [StudentAuthController::class, 'showRegistrationForm'])->name('student.register');
    Route::post('/student/register', [StudentAuthController::class, 'register'])->name('student.register');
});

// Student authenticated routes
Route::middleware(['auth:student'])->group(function () {
    // Survey routes (require student authentication)
    Route::prefix('survey')->name('survey.')->group(function () {
        Route::get('/', [SurveyController::class, 'index'])->name('index');
        Route::post('/store', [SurveyController::class, 'store'])->name('store');
        Route::get('/results', [SurveyController::class, 'results'])->name('results');
        Route::post('/validate', [SurveyController::class, 'validateForm'])->name('validate');
        Route::get('/subjects-by-teacher', [SurveyController::class, 'getSubjectsByTeacher'])->name('subjects-by-teacher');
    });
    
    Route::post('/student/logout', [StudentAuthController::class, 'logout'])->name('student.logout');
});

// Authentication routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');
    
    // Teacher management
    Route::resource('teachers', TeacherController::class);
    Route::get('/teachers-ajax', [TeacherController::class, 'getTeachers'])->name('teachers.ajax');
    Route::get('/teachers/{id}/surveys-ajax', [TeacherController::class, 'getTeacherSurveys'])->name('teachers.surveys-ajax');
    
    // Subject management
    Route::resource('subjects', SubjectController::class);
    Route::get('/subjects-ajax', [SubjectController::class, 'getSubjects'])->name('subjects.ajax');
    Route::get('/subjects/{id}/surveys-ajax', [SubjectController::class, 'getSubjectSurveys'])->name('subjects.surveys-ajax');
    
    // Student management (Admin only)
    Route::middleware('admin')->group(function () {
        Route::resource('students', StudentController::class);
        Route::get('/students-ajax', [StudentController::class, 'getStudents'])->name('students.ajax');
    });
    
    // User management
    Route::resource('users', UserController::class);
    Route::get('/users-ajax', [UserController::class, 'getUsers'])->name('users.ajax');
    
    // Survey Questions management
    Route::resource('survey-questions', SurveyQuestionController::class);
    Route::post('/survey-questions/{surveyQuestion}/toggle-status', [SurveyQuestionController::class, 'toggleStatus'])->name('survey-questions.toggle-status');
    Route::get('/survey-questions/active/list', [SurveyQuestionController::class, 'getActiveQuestions'])->name('survey-questions.active');
    
    // Survey responses
    Route::get('/surveys/{survey}/responses', [SurveyController::class, 'getResponses'])->name('surveys.responses');
    
    // Reports (Admin only)
    Route::middleware('admin')->group(function () {
        Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
        Route::post('/reports/export', [ReportsController::class, 'export'])->name('reports.export');
        Route::get('/reports/teachers-ajax', [ReportsController::class, 'getTeachersAjax'])->name('reports.teachers-ajax');
        Route::get('/reports/subjects-ajax', [ReportsController::class, 'getSubjectsAjax'])->name('reports.subjects-ajax');
        Route::get('/reports/rating-distribution', [ReportsController::class, 'getRatingDistribution'])->name('reports.rating-distribution');
        Route::get('/reports/filtered-stats', [ReportsController::class, 'getFilteredStats'])->name('reports.filtered-stats');
    });
    
    // Sentiment Words Management
    Route::prefix('sentiment-words')->name('sentiment-words.')->group(function () {
        Route::get('/', [SentimentWordController::class, 'index'])->name('index');
        Route::get('/create', [SentimentWordController::class, 'create'])->name('create');
        Route::post('/', [SentimentWordController::class, 'store'])->name('store');
        Route::get('/{sentimentWord}', [SentimentWordController::class, 'show'])->name('show');
        Route::get('/{sentimentWord}/edit', [SentimentWordController::class, 'edit'])->name('edit');
        Route::put('/{sentimentWord}', [SentimentWordController::class, 'update'])->name('update');
        Route::delete('/{sentimentWord}', [SentimentWordController::class, 'destroy'])->name('destroy');
        Route::post('/test-analysis', [SentimentWordController::class, 'testAnalysis'])->name('test-analysis');
        Route::get('/statistics', [SentimentWordController::class, 'statistics'])->name('statistics');
        Route::get('/api/list', [SentimentWordController::class, 'apiIndex'])->name('api.index');
    });
});

// Fallback route
Route::fallback(function () {
    if (auth('student')->check()) {
        return redirect()->route('survey.index');
    }
    return redirect()->route('student.login');
});

// Temporary route for adding sample data (remove in production)
Route::get('/add-sample-data', function() {
    $teachers = \App\Models\Teacher::all();
    $subjects = \App\Models\Subject::all();
    
    if ($teachers->isEmpty() || $subjects->isEmpty()) {
        return 'No teachers or subjects found.';
    }
    
    // Add sample surveys
    $sampleData = [
        ['rating' => 4.5, 'sentiment' => 'positive', 'feedback' => 'Excellent teaching!'],
        ['rating' => 3.8, 'sentiment' => 'positive', 'feedback' => 'Good course structure.'],
        ['rating' => 4.2, 'sentiment' => 'positive', 'feedback' => 'Very knowledgeable.'],
        ['rating' => 2.5, 'sentiment' => 'negative', 'feedback' => 'Could improve.'],
        ['rating' => 3.0, 'sentiment' => 'neutral', 'feedback' => 'Average course.'],
    ];
    
    foreach ($teachers as $teacher) {
        $teacherSubjects = $teacher->subjects;
        if ($teacherSubjects->isNotEmpty()) {
            for ($i = 0; $i < 2; $i++) {
                $data = $sampleData[array_rand($sampleData)];
                $subject = $teacherSubjects->random();
                
                \App\Models\Survey::create([
                    'teacher_id' => $teacher->id,
                    'subject_id' => $subject->id,
                    'rating' => $data['rating'],
                    'sentiment' => $data['sentiment'],
                    'feedback_text' => $data['feedback'],
                    'student_name' => 'Test Student ' . ($i + 1),
                    'student_email' => 'test' . ($i + 1) . '@student.edu',
                    'ip_address' => '127.0.0.1'
                ]);
            }
        }
    }
    
    return 'Sample data added successfully!';
});

// Test sentiment analysis route
Route::get('/test-sentiment', function() {
    $sentimentService = app(\App\Services\SentimentAnalysisService::class);
    
    // Test English text
    $englishText = "I love this teacher, they are excellent and very helpful!";
    $englishResult = $sentimentService->analyzeSentimentWithScore($englishText, 'en');
    
    // Test Tagalog text
    $tagalogText = "Gusto ko ang serbisyo, pero mabagal ang delivery.";
    $tagalogResult = $sentimentService->analyzeSentimentWithScore($tagalogText, 'tl');
    
    // Test with translation
    $translationResult = $sentimentService->analyzeSentimentWithTranslation($tagalogText, 'tl', 'en');
    
    return response()->json([
        'english_analysis' => $englishResult,
        'tagalog_analysis' => $tagalogResult,
        'translation_analysis' => $translationResult
    ]);
});

require __DIR__.'/auth.php';
