<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\SurveyQuestion;
use App\Models\SurveyResponse;
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
        $student = auth('student')->user();
        
        if (!$student) {
            return redirect()->route('student.login');
        }
        
        $teachers = Teacher::active()->get();
        $subjects = Subject::active()->with('teachers')->get();
        
        // Get existing surveys for this student
        $existingSurveys = Survey::where('student_id', $student->id)
            ->with(['teacher', 'subject'])
            ->get()
            ->mapWithKeys(function ($survey) {
                return [$survey->teacher_id . '_' . $survey->subject_id => true];
            });
        
        // Load questions organized by parts
        $questions = SurveyQuestion::active()->orderBy('part')->orderBy('section')->orderBy('order_number')->get();
        $questionsByPart = $questions->groupBy('part');
        
        // For backward compatibility, also provide the old format
        $optionQuestions = $questions->where('question_type', 'option');
        $commentQuestions = $questions->where('question_type', 'comment');
        
        return view('survey.index', compact('teachers', 'subjects', 'questionsByPart', 'optionQuestions', 'commentQuestions', 'existingSurveys', 'student'));
    }

    /**
     * Store a new survey submission
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'feedback_text' => 'nullable|string|max:1000',
            'student_name' => 'nullable|string|max:255',
            'student_email' => 'nullable|email|max:255',
            'year' => 'required|string|in:1st Year,2nd Year,3rd Year,4th Year',
            'course' => 'required|string|in:BSIT,BSCS',
            'question_responses' => 'nullable|array'
        ]);

        // Debug: Log validation errors if any
        if ($validator->fails()) {
            \Log::error('Survey validation failed:', $validator->errors()->toArray());
        }

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if student is authenticated
        $student = auth('student')->user();
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in as a student to submit a survey.'
            ], 401);
        }

        // Check if student has already submitted survey for this teacher and subject
        $existingSurvey = Survey::where('student_id', $student->id)
            ->where('teacher_id', $request->teacher_id)
            ->where('subject_id', $request->subject_id)
            ->first();

        if ($existingSurvey) {
            return response()->json([
                'success' => false,
                'message' => 'You have already submitted a survey for this teacher and subject. You can only submit one survey per teacher-subject combination.'
            ], 422);
        }

        try {
            // Debug: Log the incoming request data
            \Log::info('Survey submission data:', [
                'teacher_id' => $request->teacher_id,
                'subject_id' => $request->subject_id,
                'question_responses' => $request->question_responses,
                'feedback_text' => $request->feedback_text
            ]);

            $totalRating = 0;
            $ratingCount = 0;
            $allTextResponses = '';
            $questionResponses = [];

            // Process question responses and calculate rating
            if ($request->has('question_responses')) {
                foreach ($request->question_responses as $questionId => $answer) {
                    if (!empty($answer)) {
                        // Store responses for later creation
                        $questionResponses[] = [
                            'survey_question_id' => $questionId,
                            'answer' => $answer
                        ];

                        // Get question type to process rating
                        $question = SurveyQuestion::find($questionId);
                        if ($question) {
                            if ($question->question_type === 'option') {
                                // Option questions contribute directly to rating
                                $totalRating += (int)$answer;
                                $ratingCount++;
                            } else {
                                // Comment questions - add to text for sentiment analysis
                                $allTextResponses .= ' ' . $answer;
                            }
                        }
                    }
                }
            }

            // Add additional feedback text to sentiment analysis
            if ($request->filled('feedback_text')) {
                $allTextResponses .= ' ' . $request->feedback_text;
            }

            // Calculate average rating from option questions
            $averageRating = $ratingCount > 0 ? $totalRating / $ratingCount : 0;

            // Analyze sentiment from all text responses
            $sentiment = 'neutral';
            $sentimentRating = 3.0; // Default neutral rating

            if (!empty(trim($allTextResponses))) {
                try {
                    $sentiment = $this->sentimentService->analyzeSentiment($allTextResponses);
                } catch (\Exception $e) {
                    // Fallback to neutral if sentiment analysis fails
                    $sentiment = 'neutral';
                    \Log::warning('Sentiment analysis failed: ' . $e->getMessage());
                }
                
                // Convert sentiment to numerical rating
                switch ($sentiment) {
                    case 'positive':
                        $sentimentRating = 4.5;
                        break;
                    case 'negative':
                        $sentimentRating = 1.5;
                        break;
                    case 'neutral':
                    default:
                        $sentimentRating = 3.0;
                        break;
                }
            }

            // Calculate final rating: 70% from option questions, 30% from sentiment
            $finalRating = 0;
            if ($ratingCount > 0) {
                $finalRating = ($averageRating * 0.7) + ($sentimentRating * 0.3);
            } else {
                $finalRating = $sentimentRating;
            }

            // Ensure rating is within 1.0 to 5.0 range
            $finalRating = max(1.0, min(5.0, round($finalRating, 1)));

            // Use database transaction to ensure data consistency
            try {
                $survey = \DB::transaction(function() use ($request, $finalRating, $sentiment, $questionResponses, $student) {
                    // Create survey
                    $survey = Survey::create([
                        'teacher_id' => $request->teacher_id,
                        'subject_id' => $request->subject_id,
                        'student_id' => $student->id,
                        'rating' => $finalRating,
                        'sentiment' => $sentiment,
                        'feedback_text' => $request->feedback_text,
                        'student_name' => $student->name,
                        'student_email' => $student->email ?? $request->student_email,
                        'year' => $request->year,
                        'course' => $request->course,
                        'ip_address' => $request->ip()
                    ]);

                    // Create survey responses
                    foreach ($questionResponses as $response) {
                        SurveyResponse::create([
                            'survey_id' => $survey->id,
                            'survey_question_id' => $response['survey_question_id'],
                            'answer' => $response['answer']
                        ]);
                    }

                    return $survey;
                });
            } catch (\Exception $e) {
                \Log::error('Database transaction failed: ' . $e->getMessage());
                throw $e;
            }

            return response()->json([
                'success' => true,
                'message' => 'Survey submitted successfully! Thank you for your feedback.',
                'survey_id' => $survey->id,
                'calculated_rating' => $finalRating
            ]);

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Survey submission error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting the survey. Please try again.',
                'debug' => config('app.debug') ? $e->getMessage() : null
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
            'year' => 'nullable|string|in:1st Year,2nd Year,3rd Year,4th Year',
            'course' => 'nullable|string|in:BSIT,BSCS',
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

    /**
     * Get survey responses for a specific survey
     */
    public function getResponses(Survey $survey)
    {
        $responses = $survey->responses()->with('question')->get();
        
        // Group responses by part
        $part1Responses = $responses->where('question.part', 'part1');
        $part2Responses = $responses->where('question.part', 'part2');
        $part3Responses = $responses->where('question.part', 'part3');
        
        // Calculate part-specific averages
        $part1Average = $part1Responses->count() > 0 ? $part1Responses->avg('answer') : 0;
        $part2Average = $part2Responses->count() > 0 ? $part2Responses->avg('answer') : 0;
        
        // Analyze Part 3 sentiment and calculate score
        $part3Sentiment = 'neutral';
        $part3Score = 0;
        $part3Comments = $part3Responses->pluck('answer')->filter()->join(' ');
        
        if (!empty($part3Comments)) {
            $sentimentService = new \App\Services\SentimentAnalysisService();
            $analysis = $sentimentService->analyzeSentimentWithScore($part3Comments);
            $part3Sentiment = $analysis['sentiment'];
            
            // Convert sentiment to numerical score (1-5 scale)
            switch ($part3Sentiment) {
                case 'positive':
                    $part3Score = 4.5; // High positive score
                    break;
                case 'negative':
                    $part3Score = 1.5; // Low negative score
                    break;
                case 'neutral':
                default:
                    $part3Score = 3.0; // Neutral score
                    break;
            }
            
            // Adjust score based on sentiment intensity
            if (isset($analysis['score'])) {
                $sentimentIntensity = abs($analysis['score']);
                if ($sentimentIntensity > 5) {
                    // Very strong sentiment
                    if ($part3Sentiment === 'positive') {
                        $part3Score = min(5.0, $part3Score + 0.5);
                    } elseif ($part3Sentiment === 'negative') {
                        $part3Score = max(1.0, $part3Score - 0.5);
                    }
                } elseif ($sentimentIntensity < 2) {
                    // Weak sentiment
                    $part3Score = 3.0; // Move towards neutral
                }
            }
        }
        
        return view('surveys.responses', compact(
            'survey', 
            'part1Responses', 
            'part2Responses', 
            'part3Responses',
            'part1Average',
            'part2Average',
            'part3Sentiment',
            'part3Score'
        ));
    }
}
