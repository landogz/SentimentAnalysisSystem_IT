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
            'email' => 'required|email|unique:teachers,email',
            'department' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean'
        ]);

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
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the teacher.'
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
            'email' => 'required|email|unique:teachers,email,' . $id,
            'department' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean'
        ]);

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
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the teacher.'
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
}
