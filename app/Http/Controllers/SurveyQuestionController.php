<?php

namespace App\Http\Controllers;

use App\Models\SurveyQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SurveyQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questions = SurveyQuestion::orderBy('part')->orderBy('order_number')->get();
        
        // Group questions by part
        $questionsByPart = $questions->groupBy('part');
        
        return view('survey-questions.index', compact('questions', 'questionsByPart'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('survey-questions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question_text' => 'required|string|max:1000',
            'question_type' => 'required|in:option,comment',
            'part' => 'required|in:part1,part2,part3',
            'section' => 'nullable|string|max:10',
            'order_number' => 'required|integer|min:1',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $question = SurveyQuestion::create([
                'question_text' => $request->question_text,
                'question_type' => $request->question_type,
                'part' => $request->part,
                'section' => $request->section,
                'order_number' => $request->order_number,
                'is_active' => $request->boolean('is_active')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Question created successfully!',
                'question' => $question
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the question.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SurveyQuestion $surveyQuestion)
    {
        return view('survey-questions.show', compact('surveyQuestion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SurveyQuestion $surveyQuestion)
    {
        return view('survey-questions.edit', compact('surveyQuestion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SurveyQuestion $surveyQuestion)
    {
        // Debug: Log incoming request data
        \Log::info('Survey Question Update Request:', [
            'all_data' => $request->all(),
            'question_id' => $surveyQuestion->id,
            'method' => $request->method(),
            'headers' => $request->headers->all(),
            'content_type' => $request->header('Content-Type'),
            'accept' => $request->header('Accept')
        ]);
        
        // Also log individual fields
        \Log::info('Individual fields:', [
            'question_text' => $request->input('question_text'),
            'question_type' => $request->input('question_type'),
            'part' => $request->input('part'),
            'order_number' => $request->input('order_number'),
            'is_active' => $request->input('is_active'),
            '_method' => $request->input('_method'),
            '_token' => $request->input('_token')
        ]);
        
        $validator = Validator::make($request->all(), [
            'question_text' => 'required|string|max:1000',
            'question_type' => 'required|in:option,comment',
            'part' => 'required|in:part1,part2,part3',
            'section' => 'nullable|string|max:10',
            'order_number' => 'required|integer|min:1',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            \Log::warning('Survey Question Validation Failed:', [
                'errors' => $validator->errors()->toArray(),
                'data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $surveyQuestion->update([
                'question_text' => $request->question_text,
                'question_type' => $request->question_type,
                'part' => $request->part,
                'section' => $request->section,
                'order_number' => $request->order_number,
                'is_active' => $request->boolean('is_active')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Question updated successfully!',
                'question' => $surveyQuestion->fresh()
            ]);

        } catch (\Exception $e) {
            \Log::error('Survey Question Update Error: ' . $e->getMessage());
            \Log::error('Request Data: ' . json_encode($request->all()));
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the question: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SurveyQuestion $surveyQuestion)
    {
        try {
            $surveyQuestion->delete();

            return response()->json([
                'success' => true,
                'message' => 'Question deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the question.'
            ], 500);
        }
    }

    /**
     * Toggle question status
     */
    public function toggleStatus(SurveyQuestion $surveyQuestion)
    {
        try {
            $surveyQuestion->update([
                'is_active' => !$surveyQuestion->is_active
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Question status updated successfully!',
                'is_active' => $surveyQuestion->is_active
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the question status.'
            ], 500);
        }
    }

    /**
     * Get questions for survey form
     */
    public function getActiveQuestions()
    {
        $questions = SurveyQuestion::active()->orderBy('part')->orderBy('order_number')->get();
        
        // Group by part
        $questionsByPart = $questions->groupBy('part');
        
        return response()->json([
            'questions_by_part' => $questionsByPart,
            'part1_questions' => $questions->where('part', 'part1'),
            'part2_questions' => $questions->where('part', 'part2'),
            'part3_questions' => $questions->where('part', 'part3')
        ]);
    }
}
