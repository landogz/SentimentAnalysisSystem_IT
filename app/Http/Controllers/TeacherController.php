<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Teacher::query();
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
            });
        }
        
        // Filter by department
        if ($request->filled('department')) {
            $query->where('department', $request->get('department'));
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->get('status') === 'active');
        }
        
        $teachers = $query->withCount('surveys')
            ->withAvg('surveys', 'rating')
            ->orderBy('name')
            ->paginate(15);
        
        $departments = Teacher::distinct()->pluck('department');
        
        return view('teachers.index', compact('teachers', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('teachers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:teachers,email',
            'department' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean'
        ]);

        // If email is empty, remove it from validation to avoid unique constraint issues
        if (empty($request->email)) {
            $request->merge(['email' => null]);
        }

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $teacher = Teacher::create($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Teacher created successfully!',
                'teacher' => $teacher
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Teacher creation error: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the teacher: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $teacher = Teacher::with(['subjects' => function($query) {
                $query->withCount('surveys')
                      ->withAvg('surveys', 'rating');
            }, 'surveys.subject'])
            ->withCount('surveys')
            ->withAvg('surveys', 'rating')
            ->findOrFail($id);
        
        // Calculate additional statistics
        $teacher->subjects_count = $teacher->subjects->count();
        $teacher->total_surveys = $teacher->surveys_count;
        $teacher->average_rating = $teacher->surveys_avg_rating ?? 0.0;
        
        // Debug information
        \Log::info('Teacher ID: ' . $teacher->id);
        \Log::info('Subjects count: ' . $teacher->subjects_count);
        \Log::info('Subjects: ' . $teacher->subjects->pluck('name')->toJson());
        \Log::info('Surveys count: ' . $teacher->surveys_count);
        
        $sentimentStats = $teacher->getSentimentStats();
        
        return view('teachers.show', compact('teacher', 'sentimentStats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $teacher = Teacher::findOrFail($id);
        
        // Return JSON for AJAX requests
        if (request()->ajax()) {
            return response()->json($teacher);
        }
        
        return view('teachers.edit', compact('teacher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $teacher = Teacher::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:teachers,email,' . $id,
            'department' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean'
        ]);

        // If email is empty, remove it from validation to avoid unique constraint issues
        if (empty($request->email)) {
            $request->merge(['email' => null]);
        }

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $teacher->update($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Teacher updated successfully!',
                'teacher' => $teacher
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Teacher update error: ' . $e->getMessage(), [
                'teacher_id' => $id,
                'request_data' => $request->all(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the teacher: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $teacher = Teacher::findOrFail($id);
        
        // Check if teacher has surveys
        if ($teacher->surveys()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete teacher with existing surveys.'
            ], 422);
        }
        
        try {
            $teacher->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Teacher deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the teacher.'
            ], 500);
        }
    }

    /**
     * Get teachers for AJAX requests
     */
    public function getTeachers(Request $request)
    {
        $query = Teacher::active();
        
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        
        $teachers = $query->get(['id', 'name', 'department']);
        
        return response()->json($teachers);
    }

    /**
     * Get teacher surveys for DataTable AJAX requests
     */
    public function getTeacherSurveys(Request $request, string $id)
    {
        $teacher = Teacher::findOrFail($id);
        
        $query = $teacher->surveys()->with('subject');
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->whereHas('subject', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('subject_code', 'like', "%{$search}%");
            })->orWhere('feedback_text', 'like', "%{$search}%");
        }
        
        // Apply sentiment filter
        if ($request->filled('sentiment')) {
            $query->where('sentiment', $request->get('sentiment'));
        }
        
        // Apply rating filter
        if ($request->filled('rating_min')) {
            $query->where('rating', '>=', $request->get('rating_min'));
        }
        if ($request->filled('rating_max')) {
            $query->where('rating', '<=', $request->get('rating_max'));
        }
        
        // Apply date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }
        
        // Get total count before pagination
        $totalRecords = $query->count();
        
        // Apply pagination
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        
        $surveys = $query->orderBy('created_at', 'desc')
                       ->offset($start)
                       ->limit($length)
                       ->get();
        
        // Format data for DataTable
        $data = $surveys->map(function($survey) {
            $ratingStars = '';
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $survey->rating) {
                    $ratingStars .= '<i class="fas fa-star"></i>';
                } elseif ($i - 0.5 <= $survey->rating) {
                    $ratingStars .= '<i class="fas fa-star-half-alt"></i>';
                } else {
                    $ratingStars .= '<i class="far fa-star"></i>';
                }
            }
            
            $sentimentBadge = '';
            switch ($survey->sentiment) {
                case 'positive':
                    $sentimentBadge = '<span class="badge badge-success">Positive</span>';
                    break;
                case 'negative':
                    $sentimentBadge = '<span class="badge badge-danger">Negative</span>';
                    break;
                case 'neutral':
                    $sentimentBadge = '<span class="badge badge-warning">Neutral</span>';
                    break;
                default:
                    $sentimentBadge = '<span class="badge badge-secondary">Unknown</span>';
            }
            
            return [
                'date' => $survey->created_at->format('M d, Y'),
                'subject' => $survey->subject->name,
                'rating' => $ratingStars . ' <span class="ml-1">' . $survey->rating . '</span>',
                'sentiment' => $sentimentBadge,
                'feedback' => $survey->feedback_text ? 
                    '<span class="text-muted">' . \Str::limit($survey->feedback_text, 50) . '</span>' : 
                    '<span class="text-muted">No feedback</span>',
                'actions' => '<button class="btn btn-sm btn-info view-survey" data-survey-id="' . $survey->id . '">
                    <i class="fas fa-eye"></i> View
                </button>'
            ];
        });
        
        return response()->json([
            'draw' => intval($request->get('draw')),
            'recordsTotal' => $teacher->surveys()->count(),
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ]);
    }
}
