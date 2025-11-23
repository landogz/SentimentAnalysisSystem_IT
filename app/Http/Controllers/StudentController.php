<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Student::query();
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('student_number', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Filter by year
        if ($request->filled('year')) {
            $query->where('year', $request->get('year'));
        }
        
        // Filter by course/program
        if ($request->filled('course')) {
            $query->where('course', $request->get('course'));
        }
        
        $students = $query->withCount('surveys')
            ->orderBy('student_number')
            ->paginate(15);
        
        $years = Student::distinct()->whereNotNull('year')->pluck('year')->sort();
        $courses = Student::distinct()->whereNotNull('course')->pluck('course')->sort();
        
        return view('students.index', compact('students', 'years', 'courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_number' => 'required|string|unique:students,student_number|max:50',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:students,email|max:255',
            'password' => 'required|string|min:6|confirmed',
            'year' => 'required|string|in:1st Year,2nd Year,3rd Year,4th Year',
            'course' => 'required|string|in:BSIT,BSCS',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $student = Student::create([
                'student_number' => $request->student_number,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'year' => $request->year,
                'course' => $request->course,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Student created successfully!',
                'student' => $student
            ]);
        } catch (\Exception $e) {
            \Log::error('Student creation error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the student. Please try again.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $student->loadCount('surveys');
        $student->load('surveys.teacher', 'surveys.subject');
        
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        if (request()->expectsJson()) {
            return response()->json($student);
        }
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validator = Validator::make($request->all(), [
            'student_number' => 'required|string|unique:students,student_number,' . $student->id . '|max:50',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:students,email,' . $student->id . '|max:255',
            'password' => 'nullable|string|min:6|confirmed',
            'year' => 'required|string|in:1st Year,2nd Year,3rd Year,4th Year',
            'course' => 'required|string|in:BSIT,BSCS',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = [
                'student_number' => $request->student_number,
                'name' => $request->name,
                'email' => $request->email,
                'year' => $request->year,
                'course' => $request->course,
            ];
            
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }
            
            $student->update($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Student updated successfully!',
                'student' => $student->fresh()
            ]);
        } catch (\Exception $e) {
            \Log::error('Student update error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the student. Please try again.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        try {
            // Check if student has surveys
            if ($student->surveys()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete student with existing surveys. Please delete the surveys first.'
                ], 422);
            }
            
            $student->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Student deleted successfully!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Student deletion error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the student. Please try again.'
            ], 500);
        }
    }

    /**
     * Get students data for AJAX
     */
    public function getStudents(Request $request)
    {
        $query = Student::query();
        
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('student_number', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }
        
        $students = $query->orderBy('student_number')->limit(50)->get();
        
        return response()->json($students);
    }
}
