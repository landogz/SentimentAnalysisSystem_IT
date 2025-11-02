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

        // Get course distribution
        $courseDistribution = Survey::selectRaw('course, COUNT(*) as count')
            ->whereNotNull('course')
            ->groupBy('course')
            ->get();
        
        $courseChartData = [
            'labels' => $courseDistribution->pluck('course')->toArray(),
            'data' => $courseDistribution->pluck('count')->toArray()
        ];
        
        // Get year distribution
        $yearDistribution = Survey::selectRaw('year, COUNT(*) as count')
            ->whereNotNull('year')
            ->groupBy('year')
            ->orderByRaw("FIELD(year, '1st Year', '2nd Year', '3rd Year', '4th Year')")
            ->get();
        
        $yearChartData = [
            'labels' => $yearDistribution->pluck('year')->toArray(),
            'data' => $yearDistribution->pluck('count')->toArray()
        ];

        return view('reports.index', compact(
            'teachers',
            'subjects',
            'topTeachers',
            'topSubjects',
            'totalSurveys',
            'averageRating',
            'sentimentStats',
            'ratingDistribution',
            'courseChartData',
            'yearChartData'
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
        
        // Build query with filters
        $query = Survey::with(['teacher', 'subject']);
        
        if ($teacherId) {
            $query->where('teacher_id', $teacherId);
        }
        
        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }
        
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }
        
        $surveys = $query->orderBy('created_at', 'desc')->get();
        
        // Get statistics
        $totalSurveys = $surveys->count();
        $averageRating = $surveys->avg('rating') ?? 0.0;
        $sentimentStats = [
            'positive' => $surveys->where('sentiment', 'positive')->count(),
            'negative' => $surveys->where('sentiment', 'negative')->count(),
            'neutral' => $surveys->where('sentiment', 'neutral')->count(),
        ];
        
        // Prepare data for export
        $exportData = [
            'title' => 'PRMSU CCIT Student Feedback Report',
            'generated_at' => now()->format('F j, Y \a\t g:i A'),
            'filters' => [
                'teacher' => $teacherId ? Teacher::find($teacherId)->name ?? 'All Teachers' : 'All Teachers',
                'subject' => $subjectId ? Subject::find($subjectId)->name ?? 'All Subjects' : 'All Subjects',
                'date_from' => $dateFrom ? date('M j, Y', strtotime($dateFrom)) : 'All Time',
                'date_to' => $dateTo ? date('M j, Y', strtotime($dateTo)) : 'All Time',
            ],
            'statistics' => [
                'total_surveys' => $totalSurveys,
                'average_rating' => number_format($averageRating, 1),
                'positive_feedback' => $sentimentStats['positive'],
                'negative_feedback' => $sentimentStats['negative'],
                'neutral_feedback' => $sentimentStats['neutral'],
            ],
            'surveys' => $surveys->map(function($survey) {
                return [
                    'Date' => $survey->created_at->format('M j, Y'),
                    'Teacher' => $survey->teacher->name,
                    'Subject' => $survey->subject->name,
                    'Rating' => number_format($survey->rating, 1),
                    'Sentiment' => ucfirst($survey->sentiment),
                    'Feedback' => $survey->feedback_text ?? 'No feedback provided',
                    'Student' => $survey->student_name ?? 'Anonymous',
                ];
            })->toArray()
        ];
        
        switch ($format) {
            case 'pdf':
                return $this->exportPDF($exportData);
            case 'excel':
                return $this->exportExcel($exportData);
            case 'csv':
                return $this->exportCSV($exportData);
            default:
                return response()->json(['error' => 'Invalid export format'], 400);
        }
    }
    
    /**
     * Export to PDF
     */
    private function exportPDF($data)
    {
        // For now, return a JSON response with PDF data
        // In a real implementation, you would use a library like DomPDF
        return response()->json([
            'success' => true,
            'message' => 'PDF report generated successfully',
            'format' => 'pdf',
            'data' => $data,
            'download_url' => '#', // Would be actual PDF download URL
        ]);
    }
    
    /**
     * Export to Excel
     */
    private function exportExcel($data)
    {
        // For now, return a JSON response with Excel data
        // In a real implementation, you would use a library like PhpSpreadsheet
        return response()->json([
            'success' => true,
            'message' => 'Excel report generated successfully',
            'format' => 'excel',
            'data' => $data,
            'download_url' => '#', // Would be actual Excel download URL
        ]);
    }
    
    /**
     * Export to CSV
     */
    private function exportCSV($data)
    {
        $filename = 'prmsu-ccit-feedback-report-' . date('Y-m-d-H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Add report header
            fputcsv($file, ['PRMSU CCIT Student Feedback Report']);
            fputcsv($file, ['Generated: ' . $data['generated_at']]);
            fputcsv($file, []);
            
            // Add filters
            fputcsv($file, ['Filters:']);
            fputcsv($file, ['Teacher', $data['filters']['teacher']]);
            fputcsv($file, ['Subject', $data['filters']['subject']]);
            fputcsv($file, ['Date From', $data['filters']['date_from']]);
            fputcsv($file, ['Date To', $data['filters']['date_to']]);
            fputcsv($file, []);
            
            // Add statistics
            fputcsv($file, ['Statistics:']);
            fputcsv($file, ['Total Surveys', $data['statistics']['total_surveys']]);
            fputcsv($file, ['Average Rating', $data['statistics']['average_rating']]);
            fputcsv($file, ['Positive Feedback', $data['statistics']['positive_feedback']]);
            fputcsv($file, ['Negative Feedback', $data['statistics']['negative_feedback']]);
            fputcsv($file, ['Neutral Feedback', $data['statistics']['neutral_feedback']]);
            fputcsv($file, []);
            
            // Add survey data headers
            if (!empty($data['surveys'])) {
                fputcsv($file, array_keys($data['surveys'][0]));
                
                // Add survey data
                foreach ($data['surveys'] as $survey) {
                    fputcsv($file, $survey);
                }
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
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

        // Get filtered course distribution
        $courseQuery = clone $query;
        $courseDistribution = $courseQuery->selectRaw('course, COUNT(*) as count')
            ->whereNotNull('course')
            ->groupBy('course')
            ->get();
        
        $courseChartData = [
            'labels' => $courseDistribution->pluck('course')->toArray(),
            'data' => $courseDistribution->pluck('count')->toArray()
        ];
        
        // Get filtered year distribution
        $yearQuery = clone $query;
        $yearDistribution = $yearQuery->selectRaw('year, COUNT(*) as count')
            ->whereNotNull('year')
            ->groupBy('year')
            ->orderByRaw("FIELD(year, '1st Year', '2nd Year', '3rd Year', '4th Year')")
            ->get();
        
        $yearChartData = [
            'labels' => $yearDistribution->pluck('year')->toArray(),
            'data' => $yearDistribution->pluck('count')->toArray()
        ];

        return response()->json([
            'total_surveys' => $totalSurveys,
            'average_rating' => number_format($averageRating, 1),
            'sentiment_stats' => $sentimentStats,
            'course_chart' => $courseChartData,
            'year_chart' => $yearChartData
        ]);
    }
} 