<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Teacher;
use App\Models\Subject;
use App\Services\SentimentAnalysisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SurveyController extends Controller
{
    protected $sentimentService;

    public function __construct(SentimentAnalysisService $sentimentService)
    {
        $this->sentimentService = $sentimentService;
    }

    /**
     * Display the public survey form
     */
    public function index()
    {
        $teachers = Teacher::active()->get();
        $subjects = Subject::active()->with('teachers')->get();
        
        return view('survey.index', compact('teachers', 'subjects'));
    }

    /**
     * Store a new survey submission
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'rating' => 'required|numeric|min:1.0|max:5.0',
            'feedback_text' => 'nullable|string|max:1000',
            'student_name' => 'nullable|string|max:255',
            'student_email' => 'nullable|email|max:255',
            'survey_responses' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Analyze sentiment from feedback text
            $sentiment = 'neutral';
            if ($request->filled('feedback_text')) {
                $sentiment = $this->sentimentService->analyzeSentiment($request->feedback_text);
            }

            // Create survey
            $survey = Survey::create([
                'teacher_id' => $request->teacher_id,
                'subject_id' => $request->subject_id,
                'rating' => $request->rating,
                'sentiment' => $sentiment,
                'feedback_text' => $request->feedback_text,
                'survey_responses' => $request->survey_responses,
                'student_name' => $request->student_name,
                'student_email' => $request->student_email,
                'ip_address' => $request->ip()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Survey submitted successfully! Thank you for your feedback.',
                'survey_id' => $survey->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting the survey. Please try again.'
            ], 500);
        }
    }

    /**
     * Get subjects for a specific teacher
     */
    public function getSubjectsByTeacher(Request $request)
    {
        $teacherId = $request->get('teacher_id');
        
        $subjects = Subject::whereHas('teachers', function($query) use ($teacherId) {
            $query->where('teachers.id', $teacherId);
        })
        ->active()
        ->get(['id', 'name', 'subject_code']);
        
        return response()->json($subjects);
    }

    /**
     * Display survey results (public view)
     */
    public function results()
    {
        $totalSurveys = Survey::count();
        $averageRating = Survey::avg('rating') ?? 0.0;
        
        $sentimentStats = [
            'positive' => Survey::where('sentiment', 'positive')->count(),
            'negative' => Survey::where('sentiment', 'negative')->count(),
            'neutral' => Survey::where('sentiment', 'neutral')->count(),
        ];
        
        $topTeachers = Teacher::withCount('surveys')
            ->withAvg('surveys', 'rating')
            ->having('surveys_count', '>', 0)
            ->orderBy('surveys_avg_rating', 'desc')
            ->limit(10)
            ->get();
        
        return view('survey.results', compact(
            'totalSurveys',
            'averageRating',
            'sentimentStats',
            'topTeachers'
        ));
    }

    /**
     * Validate survey form via AJAX
     */
    public function validateForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'rating' => 'required|numeric|min:1.0|max:5.0',
            'feedback_text' => 'nullable|string|max:1000',
            'student_name' => 'nullable|string|max:255',
            'student_email' => 'nullable|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'valid' => false,
                'errors' => $validator->errors()
            ]);
        }

        return response()->json([
            'valid' => true
        ]);
    }
}
