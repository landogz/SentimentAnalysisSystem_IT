<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class StudentAuthController extends Controller
{
    /**
     * Show student login form
     */
    public function showLoginForm()
    {
        return view('auth.student-login');
    }

    /**
     * Handle student login
     */
    public function login(Request $request)
    {
        $request->validate([
            'student_number' => 'required|string',
            'password' => 'required|string',
        ]);

        $student = Student::where('student_number', $request->student_number)->first();

        if (!$student || !Hash::check($request->password, $student->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid student number or password.'
            ], 422);
        }

        Auth::guard('student')->login($student);
        $request->session()->regenerate();

        // Update last login timestamp
        $student->update(['last_login_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Login successful!',
            'redirect' => route('survey.index')
        ]);
    }

    /**
     * Show student registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.student-register');
    }

    /**
     * Handle student registration
     */
    public function register(Request $request)
    {
        $request->validate([
            'student_number' => 'required|string|unique:students,student_number|max:50',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:students,email|max:255',
            'password' => 'required|string|min:6|confirmed',
            'year' => 'required|string|in:1st Year,2nd Year,3rd Year,4th Year',
            'course' => 'required|string|in:BSIT,BSCS',
        ]);

        try {
            $student = Student::create([
                'student_number' => $request->student_number,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'year' => $request->year,
                'course' => $request->course,
            ]);

            // Auto-login after registration
            Auth::guard('student')->login($student);

            return response()->json([
                'success' => true,
                'message' => 'Registration successful! Welcome to the Student Feedback System.',
                'redirect' => route('survey.index')
            ]);
        } catch (\Exception $e) {
            \Log::error('Student registration failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again.'
            ], 500);
        }
    }

    /**
     * Handle student logout
     */
    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully!',
            'redirect' => route('student.login')
        ]);
    }
}
