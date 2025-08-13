<?php

namespace App\Http\Controllers;

use App\Models\SentimentWord;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class SentimentWordController extends Controller
{
    /**
     * Display a listing of sentiment words
     */
    public function index(Request $request): View
    {
        $query = SentimentWord::query();

        // Search by word
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('word', 'LIKE', "%{$search}%");
        }

        // Filter by type
        if ($request->filled('type') && in_array($request->type, ['positive', 'negative', 'neutral'])) {
            $query->where('type', $request->type);
        }

        // Filter by language
        if ($request->filled('language')) {
            $query->where('language', $request->language);
        }

        // Filter by active status
        if ($request->has('active')) {
            $activeValue = $request->active;
            
            // Handle empty string case (when "All Status" is selected)
            if ($activeValue === '') {
                // Don't filter - show all
            } elseif ($activeValue === '1' || $activeValue === 'true') {
                $query->where('is_active', true);
            } elseif ($activeValue === '0' || $activeValue === 'false') {
                $query->where('is_active', false);
            }
        }

        $words = $query->orderBy('word')->paginate(20);

        return view('sentiment-words.index', compact('words'));
    }

    /**
     * Show the form for creating a new sentiment word
     */
    public function create(): View
    {
        return view('sentiment-words.create');
    }

    /**
     * Store a newly created sentiment word
     */
    public function store(Request $request)
    {
        $request->validate([
            'word' => 'required|string|max:255',
            'negation' => 'nullable|string|max:255',
            'type' => 'required|in:positive,negative,neutral',
            'score' => 'required|numeric|between:-5,5',
            'language' => 'required|string|max:10'
        ]);

        try {
            $word = SentimentWord::create([
                'word' => strtolower($request->word),
                'negation' => $request->negation ? strtolower($request->negation) : null,
                'type' => $request->type,
                'score' => $request->score,
                'language' => $request->language,
                'is_active' => true
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sentiment word created successfully',
                    'data' => $word
                ], 201);
            }

            return redirect()->route('sentiment-words.index')
                ->with('success', 'Sentiment word created successfully');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating sentiment word: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->with('error', 'Error creating sentiment word: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified sentiment word
     */
    public function edit(SentimentWord $sentimentWord): View
    {
        return view('sentiment-words.edit', compact('sentimentWord'));
    }

    /**
     * Update the specified sentiment word
     */
    public function update(Request $request, SentimentWord $sentimentWord)
    {
        $request->validate([
            'word' => 'sometimes|string|max:255',
            'negation' => 'nullable|string|max:255',
            'type' => 'sometimes|in:positive,negative,neutral',
            'score' => 'sometimes|numeric|between:-5,5',
            'language' => 'sometimes|string|max:10',
            'is_active' => 'sometimes|boolean'
        ]);

        try {
            $data = $request->only(['word', 'type', 'score', 'language', 'is_active']);
            if ($request->has('negation')) {
                $data['negation'] = $request->negation ? strtolower($request->negation) : null;
            }
            
            $sentimentWord->update($data);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sentiment word updated successfully',
                    'data' => $sentimentWord
                ]);
            }

            return redirect()->route('sentiment-words.index')
                ->with('success', 'Sentiment word updated successfully');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating sentiment word: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->with('error', 'Error updating sentiment word: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified sentiment word
     */
    public function destroy(Request $request, SentimentWord $sentimentWord)
    {
        try {
            $sentimentWord->delete();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sentiment word deleted successfully'
                ]);
            }

            return redirect()->route('sentiment-words.index')
                ->with('success', 'Sentiment word deleted successfully');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting sentiment word: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error deleting sentiment word: ' . $e->getMessage());
        }
    }

    /**
     * Show the specified sentiment word
     */
    public function show(SentimentWord $sentimentWord): View
    {
        return view('sentiment-words.show', compact('sentimentWord'));
    }

    /**
     * Test sentiment analysis with custom text
     */
    public function testAnalysis(Request $request): JsonResponse
    {
        $request->validate([
            'text' => 'required|string',
            'language' => 'sometimes|string|max:10',
            'translate' => 'sometimes'
        ]);

        $sentimentService = app(\App\Services\SentimentAnalysisService::class);
        
        $text = $request->text;
        $language = $request->language ?? 'en';
        $translate = $request->boolean('translate', false);

        if ($translate && $language !== 'en') {
            $result = $sentimentService->analyzeSentimentWithTranslation($text, $language, 'en');
        } else {
            $result = $sentimentService->analyzeSentimentWithScore($text, $language);
        }

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Get statistics for sentiment words
     */
    public function statistics(): JsonResponse
    {
        $stats = [
            'total' => SentimentWord::count(),
            'by_type' => [
                'positive' => SentimentWord::where('type', 'positive')->count(),
                'negative' => SentimentWord::where('type', 'negative')->count(),
                'neutral' => SentimentWord::where('type', 'neutral')->count(),
            ],
            'by_language' => SentimentWord::selectRaw('language, COUNT(*) as count')
                ->groupBy('language')
                ->pluck('count', 'language')
                ->toArray(),
            'active' => SentimentWord::where('is_active', true)->count(),
            'inactive' => SentimentWord::where('is_active', false)->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * API endpoint for listing words
     */
    public function apiIndex(Request $request): JsonResponse
    {
        $query = SentimentWord::query();

        // Filter by type
        if ($request->has('type') && in_array($request->type, ['positive', 'negative', 'neutral'])) {
            $query->where('type', $request->type);
        }

        // Filter by language
        if ($request->has('language')) {
            $query->where('language', $request->language);
        }

        // Filter by active status
        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        $words = $query->orderBy('word')->paginate(50);

        return response()->json([
            'success' => true,
            'data' => $words
        ]);
    }
} 