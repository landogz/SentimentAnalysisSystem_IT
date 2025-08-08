<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Subject::with('teachers');
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('subject_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Filter by teacher
        if ($request->filled('teacher_id')) {
            $query->whereHas('teachers', function($q) use ($request) {
                $q->where('teachers.id', $request->get('teacher_id'));
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->get('status') === 'active');
        }
        
        $subjects = $query->withCount('surveys')
            ->withAvg('surveys', 'rating')
            ->orderBy('name')
            ->paginate(15);
        
        $teachers = Teacher::active()->get();
        
        return view('subjects.index', compact('subjects', 'teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teachers = Teacher::active()->get();
        return view('subjects.create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_code' => 'required|string|max:50|unique:subjects,subject_code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'teacher_ids' => 'required|array|min:1',
            'teacher_ids.*' => 'exists:teachers,id',
            'is_active' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $subject = Subject::create([
                'subject_code' => $request->subject_code,
                'name' => $request->name,
                'description' => $request->description,
                'is_active' => $request->has('is_active')
            ]);

            // Attach teachers to the subject
            $teacherIds = $request->teacher_ids;
            $primaryTeacherId = $request->primary_teacher_id ?? $teacherIds[0];
            
            $pivotData = [];
            foreach ($teacherIds as $teacherId) {
                $pivotData[$teacherId] = [
                    'is_primary' => ($teacherId == $primaryTeacherId),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            
            $subject->teachers()->attach($pivotData);
            
            return response()->json([
                'success' => true,
                'message' => 'Subject created successfully!',
                'subject' => $subject->load('teachers')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the subject.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $subject = Subject::with(['teachers' => function($query) {
                $query->withCount('surveys')
                      ->withAvg('surveys', 'rating');
            }, 'surveys'])
            ->withCount('surveys')
            ->withAvg('surveys', 'rating')
            ->findOrFail($id);
        
        $sentimentStats = $subject->getSentimentStats();
        
        return view('subjects.show', compact('subject', 'sentimentStats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $subject = Subject::findOrFail($id);
        
        // Return JSON for AJAX requests
        if (request()->ajax()) {
            return response()->json($subject->load('teachers'));
        }
        
        $teachers = Teacher::active()->get();
        return view('subjects.edit', compact('subject', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $subject = Subject::findOrFail($id);
        
        // Debug: Log the request data
        \Log::info('Update subject request data:', $request->all());
        
        $validator = Validator::make($request->all(), [
            'subject_code' => 'required|string|max:50|unique:subjects,subject_code,' . $id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'teacher_ids' => 'required|array|min:1',
            'teacher_ids.*' => 'exists:teachers,id',
            'is_active' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            \Log::error('Subject update validation failed:', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $subject->update([
                'subject_code' => $request->subject_code,
                'name' => $request->name,
                'description' => $request->description,
                'is_active' => $request->has('is_active')
            ]);

            // Sync teachers for the subject
            $teacherIds = $request->teacher_ids;
            $primaryTeacherId = $request->primary_teacher_id ?? $teacherIds[0];
            
            $pivotData = [];
            foreach ($teacherIds as $teacherId) {
                $pivotData[$teacherId] = [
                    'is_primary' => ($teacherId == $primaryTeacherId),
                    'updated_at' => now()
                ];
            }
            
            $subject->teachers()->sync($pivotData);
            
            return response()->json([
                'success' => true,
                'message' => 'Subject updated successfully!',
                'subject' => $subject->load('teachers')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the subject.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subject = Subject::findOrFail($id);
        
        // Check if subject has surveys
        if ($subject->surveys()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete subject with existing surveys.'
            ], 422);
        }
        
        try {
            $subject->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Subject deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the subject.'
            ], 500);
        }
    }

    /**
     * Get subjects for AJAX requests
     */
    public function getSubjects(Request $request)
    {
        $query = Subject::active()->with('teachers');
        
        if ($request->filled('teacher_id')) {
            $query->whereHas('teachers', function($q) use ($request) {
                $q->where('teachers.id', $request->teacher_id);
            });
        }
        
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        
        $subjects = $query->get(['id', 'name', 'subject_code']);
        
        return response()->json($subjects);
    }
}
