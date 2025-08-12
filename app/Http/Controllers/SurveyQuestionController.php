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
        $questions = SurveyQuestion::orderBy('order_number')->get();
        return view('survey-questions.index', compact('questions'));
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
        $validator = Validator::make($request->all(), [
            'question_text' => 'required|string|max:1000',
            'question_type' => 'required|in:option,comment',
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
            $surveyQuestion->update([
                'question_text' => $request->question_text,
                'question_type' => $request->question_type,
                'order_number' => $request->order_number,
                'is_active' => $request->boolean('is_active')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Question updated successfully!',
                'question' => $surveyQuestion->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the question.'
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
        $optionQuestions = SurveyQuestion::active()->optionType()->orderBy('order_number')->get();
        $commentQuestions = SurveyQuestion::active()->commentType()->orderBy('order_number')->get();

        return response()->json([
            'option_questions' => $optionQuestions,
            'comment_questions' => $commentQuestions
        ]);
    }
}
