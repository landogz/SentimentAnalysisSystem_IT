<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard
     */
    public function index()
    {
        // Get total statistics
        $totalSurveys = Survey::count();
        $totalTeachers = Teacher::count();
        $totalSubjects = Subject::count();
        
        // Get average rating
        $averageRating = Survey::avg('rating') ?? 0.0;
        
        // Get sentiment statistics
        $sentimentStats = [
            'positive' => Survey::where('sentiment', 'positive')->count(),
            'negative' => Survey::where('sentiment', 'negative')->count(),
            'neutral' => Survey::where('sentiment', 'neutral')->count(),
        ];
        
        // Get top rated teachers
        $topTeachers = Teacher::withCount('surveys')
            ->withAvg('surveys', 'rating')
            ->having('surveys_count', '>', 0)
            ->orderBy('surveys_avg_rating', 'desc')
            ->limit(5)
            ->get();
        
        // Get top rated subjects
        $topSubjects = Subject::withCount('surveys')
            ->withAvg('surveys', 'rating')
            ->having('surveys_count', '>', 0)
            ->orderBy('surveys_avg_rating', 'desc')
            ->limit(5)
            ->get();
        
        // Get recent surveys
        $recentSurveys = Survey::with(['teacher', 'subject'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Get monthly survey trends
        $monthlyTrendsRaw = Survey::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        // Format monthly trends for chart
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $monthlyTrends = [
            'labels' => [],
            'data' => []
        ];
        
        // Initialize all months with 0 count
        for ($i = 1; $i <= 12; $i++) {
            $monthlyTrends['labels'][] = $monthNames[$i - 1];
            $monthlyTrends['data'][] = 0;
        }
        
        // Fill in actual data
        foreach ($monthlyTrendsRaw as $trend) {
            $monthlyTrends['data'][$trend->month - 1] = $trend->count;
        }
        
        // Get CS (BSCS) distribution by year level
        $csDistribution = Survey::selectRaw('year, COUNT(*) as count')
            ->where('course', 'BSCS')
            ->whereNotNull('year')
            ->groupBy('year')
            ->orderByRaw("FIELD(year, '1st Year', '2nd Year', '3rd Year', '4th Year')")
            ->get();
        
        // Initialize all year levels with 0 count for CS
        $csChartData = [
            'labels' => ['1st Year', '2nd Year', '3rd Year', '4th Year'],
            'data' => [0, 0, 0, 0]
        ];
        
        // Fill in actual CS data
        foreach ($csDistribution as $dist) {
            $yearIndex = array_search($dist->year, $csChartData['labels']);
            if ($yearIndex !== false) {
                $csChartData['data'][$yearIndex] = $dist->count;
            }
        }
        
        // Get IT (BSIT) distribution by year level
        $itDistribution = Survey::selectRaw('year, COUNT(*) as count')
            ->where('course', 'BSIT')
            ->whereNotNull('year')
            ->groupBy('year')
            ->orderByRaw("FIELD(year, '1st Year', '2nd Year', '3rd Year', '4th Year')")
            ->get();
        
        // Initialize all year levels with 0 count for IT
        $itChartData = [
            'labels' => ['1st Year', '2nd Year', '3rd Year', '4th Year'],
            'data' => [0, 0, 0, 0]
        ];
        
        // Fill in actual IT data
        foreach ($itDistribution as $dist) {
            $yearIndex = array_search($dist->year, $itChartData['labels']);
            if ($yearIndex !== false) {
                $itChartData['data'][$yearIndex] = $dist->count;
            }
        }
        
        // Keep old variable names for backward compatibility but use new data
        $courseChartData = $csChartData;
        $yearChartData = $itChartData;
        
        return view('dashboard.index', compact(
            'totalSurveys',
            'totalTeachers', 
            'totalSubjects',
            'averageRating',
            'sentimentStats',
            'topTeachers',
            'topSubjects',
            'recentSurveys',
            'monthlyTrends',
            'courseChartData',
            'yearChartData'
        ));
    }

    /**
     * Get dashboard statistics via AJAX
     */
    public function getStats(Request $request)
    {
        $teacherId = $request->get('teacher_id');
        $subjectId = $request->get('subject_id');
        
        $query = Survey::query();
        
        if ($teacherId) {
            $query->where('teacher_id', $teacherId);
        }
        
        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }
        
        $stats = [
            'total_surveys' => $query->count(),
            'average_rating' => round($query->avg('rating') ?? 0, 1),
            'sentiment_stats' => [
                'positive' => $query->where('sentiment', 'positive')->count(),
                'negative' => $query->where('sentiment', 'negative')->count(),
                'neutral' => $query->where('sentiment', 'neutral')->count(),
            ]
        ];
        
        return response()->json($stats);
    }

    /**
     * Get chart data for dashboard
     */
    public function getChartData()
    {
        // Sentiment distribution
        $sentimentData = Survey::selectRaw('sentiment, COUNT(*) as count')
            ->groupBy('sentiment')
            ->get();
        
        // Rating distribution
        $ratingData = Survey::selectRaw('ROUND(rating, 0) as rating_group, COUNT(*) as count')
            ->groupBy('rating_group')
            ->orderBy('rating_group')
            ->get();
        
        // Monthly trends
        $monthlyData = Survey::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        return response()->json([
            'sentiment' => $sentimentData,
            'rating' => $ratingData,
            'monthly' => $monthlyData
        ]);
    }
}
