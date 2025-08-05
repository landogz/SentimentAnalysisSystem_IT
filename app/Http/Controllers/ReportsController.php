<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    /**
     * Display the reports index page
     */
    public function index()
    {
        $teachers = Teacher::active()->get();
        $subjects = Subject::with('teachers')->active()->get();
        
        return view('reports.index', compact('teachers', 'subjects'));
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
} 