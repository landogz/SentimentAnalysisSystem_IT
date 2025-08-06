<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    /**
     * Display the reports index page
     */
    public function index()
    {
        $teachers = Teacher::active()->get();
        $subjects = Subject::with('teachers')->active()->get();

        // Get top rated teachers with survey data
        $topTeachers = Teacher::withCount('surveys')
            ->withAvg('surveys', 'rating')
            ->having('surveys_count', '>', 0)
            ->orderBy('surveys_avg_rating', 'desc')
            ->limit(5)
            ->get();

        // Get top rated subjects with survey data
        $topSubjects = Subject::withCount('surveys')
            ->withAvg('surveys', 'rating')
            ->with('teachers')
            ->having('surveys_count', '>', 0)
            ->orderBy('surveys_avg_rating', 'desc')
            ->limit(5)
            ->get();

        // Get overall statistics
        $totalSurveys = Survey::count();
        $averageRating = Survey::avg('rating') ?? 0.0;
        $sentimentStats = [
            'positive' => Survey::where('sentiment', 'positive')->count(),
            'negative' => Survey::where('sentiment', 'negative')->count(),
            'neutral' => Survey::where('sentiment', 'neutral')->count(),
        ];

        // Get rating distribution
        $ratingDistribution = Survey::selectRaw('
            CASE 
                WHEN rating >= 1 AND rating < 2 THEN 1
                WHEN rating >= 2 AND rating < 3 THEN 2
                WHEN rating >= 3 AND rating < 4 THEN 3
                WHEN rating >= 4 AND rating < 5 THEN 4
                WHEN rating >= 5 THEN 5
                ELSE 0
            END as rating_group,
            COUNT(*) as count
        ')
        ->whereNotNull('rating')
        ->groupBy('rating_group')
        ->orderBy('rating_group')
        ->get();

        return view('reports.index', compact(
            'teachers',
            'subjects',
            'topTeachers',
            'topSubjects',
            'totalSurveys',
            'averageRating',
            'sentimentStats',
            'ratingDistribution'
        ));
    }

    /**
     * Get teachers data for AJAX
     */
    public function getTeachersAjax()
    {
        $teachers = Teacher::withCount('surveys')
            ->withAvg('surveys', 'rating')
            ->orderBy('surveys_avg_rating', 'desc')
            ->get()
            ->map(function($teacher) {
                return [
                    'id' => $teacher->id,
                    'name' => $teacher->name,
                    'department' => $teacher->department,
                    'surveys_count' => $teacher->surveys_count,
                    'surveys_avg_rating' => $teacher->surveys_avg_rating ?? 0
                ];
            });
        
        return response()->json($teachers);
    }

    /**
     * Get subjects data for AJAX
     */
    public function getSubjectsAjax()
    {
        $subjects = Subject::withCount('surveys')
            ->withAvg('surveys', 'rating')
            ->with('teachers')
            ->orderBy('surveys_avg_rating', 'desc')
            ->get()
            ->map(function($subject) {
                return [
                    'id' => $subject->id,
                    'name' => $subject->name,
                    'subject_code' => $subject->subject_code,
                    'surveys_count' => $subject->surveys_count,
                    'surveys_avg_rating' => $subject->surveys_avg_rating ?? 0,
                    'teachers' => $subject->teachers->map(function($teacher) {
                        return [
                            'id' => $teacher->id,
                            'name' => $teacher->name,
                            'is_primary' => $teacher->pivot->is_primary ?? false
                        ];
                    })
                ];
            });
        
        return response()->json($subjects);
    }

    /**
     * Export reports
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'pdf');
        $teacherId = $request->get('teacher_id');
        $subjectId = $request->get('subject_id');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        // Here you would implement the actual export logic
        // For now, we'll just return a success message
        
        return response()->json([
            'success' => true,
            'message' => "Report exported successfully in {$format} format.",
            'data' => [
                'teacher_id' => $teacherId,
                'subject_id' => $subjectId,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'format' => $format
            ]
        ]);
    }

    /**
     * Get rating distribution data for AJAX
     */
    public function getRatingDistribution(Request $request)
    {
        $query = Survey::query();

        // Apply filters
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $ratingDistribution = $query->selectRaw('
            CASE 
                WHEN rating >= 1 AND rating < 2 THEN 1
                WHEN rating >= 2 AND rating < 3 THEN 2
                WHEN rating >= 3 AND rating < 4 THEN 3
                WHEN rating >= 4 AND rating < 5 THEN 4
                WHEN rating >= 5 THEN 5
                ELSE 0
            END as rating_group,
            COUNT(*) as count
        ')
        ->whereNotNull('rating')
        ->groupBy('rating_group')
        ->orderBy('rating_group')
        ->get();

        // Format data for chart
        $chartData = [0, 0, 0, 0, 0]; // Initialize with zeros for all 5 ratings
        
        foreach ($ratingDistribution as $item) {
            if ($item->rating_group >= 1 && $item->rating_group <= 5) {
                $chartData[$item->rating_group - 1] = $item->count;
            }
        }

        return response()->json($chartData);
    }

    /**
     * Get filtered statistics for AJAX
     */
    public function getFilteredStats(Request $request)
    {
        $query = Survey::query();

        // Apply filters
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $totalSurveys = $query->count();
        $averageRating = $query->avg('rating') ?? 0.0;
        
        $sentimentStats = [
            'positive' => (clone $query)->where('sentiment', 'positive')->count(),
            'negative' => (clone $query)->where('sentiment', 'negative')->count(),
            'neutral' => (clone $query)->where('sentiment', 'neutral')->count(),
        ];

        return response()->json([
            'total_surveys' => $totalSurveys,
            'average_rating' => number_format($averageRating, 1),
            'sentiment_stats' => $sentimentStats
        ]);
    }
} 