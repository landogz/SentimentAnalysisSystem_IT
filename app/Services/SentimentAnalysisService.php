<?php

namespace App\Services;

use App\Models\SentimentWord;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SentimentAnalysisService
{
    private array $positiveWords = [
        'excellent', 'great', 'amazing', 'outstanding', 'fantastic', 'wonderful',
        'brilliant', 'superb', 'perfect', 'awesome', 'incredible', 'terrific',
        'helpful', 'knowledgeable', 'patient', 'clear', 'organized', 'engaging',
        'inspiring', 'motivating', 'professional', 'dedicated', 'caring', 'supportive',
        'enthusiastic', 'passionate', 'creative', 'innovative', 'effective', 'efficient',
        'thorough', 'detailed', 'comprehensive', 'well-prepared', 'punctual', 'reliable'
    ];

    private array $negativeWords = [
        'poor', 'terrible', 'awful', 'horrible', 'bad', 'worst', 'disappointing',
        'confusing', 'unclear', 'disorganized', 'boring', 'monotone', 'unprepared',
        'late', 'unreliable', 'unprofessional', 'rude', 'impatient', 'unhelpful',
        'ineffective', 'waste', 'useless', 'difficult', 'complicated', 'overwhelming',
        'stressful', 'frustrating', 'annoying', 'irritating', 'unfair', 'biased',
        'incompetent', 'unqualified', 'lazy', 'careless', 'negligent'
    ];

    private array $neutralWords = [
        'okay', 'fine', 'average', 'normal', 'standard', 'regular', 'usual',
        'adequate', 'satisfactory', 'acceptable', 'reasonable', 'moderate',
        'decent', 'fair', 'balanced', 'neutral', 'indifferent', 'mixed'
    ];

    /**
     * Translate text using Google Translate API
     */
    public function translateText(string $text, string $sourceLang = 'tl', string $targetLang = 'en'): ?string
    {
        try {
            $apiKey = config('services.google.translate_api_key');
            
            if (!$apiKey) {
                Log::warning('Google Translate API key not configured');
                return null;
            }

            $response = Http::post('https://translation.googleapis.com/language/translate/v2', [
                'q' => $text,
                'source' => $sourceLang,
                'target' => $targetLang,
                'format' => 'text',
                'key' => $apiKey
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['data']['translations'][0]['translatedText'] ?? null;
            }

            Log::error('Translation API error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Translation error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get words with scores from database
     */
    private function getWordsWithScores(string $language = 'en'): array
    {
        return SentimentWord::getAllWordsWithScores($language);
    }

    /**
     * Analyze sentiment with scoring system
     */
    public function analyzeSentimentWithScore(string $text, string $language = 'en'): array
    {
        $text = strtolower(trim($text));
        $words = preg_split('/\s+/', $text);
        
        $score = 0;
        $positiveScore = 0;
        $negativeScore = 0;
        $neutralScore = 0;
        $matchedWords = [];

        // Get words from database if available
        $dbWords = $this->getWordsWithScores($language);
        
        // Get negation pairs
        $negationPairs = SentimentWord::getNegationPairs($language);
        
        // Common negation words
        $negationWords = ['not', 'no', 'never', 'none', 'neither', 'nor', 'nobody', 'nothing', 'nowhere', 'hardly', 'barely', 'scarcely', 'rarely', 'seldom'];
        
        for ($i = 0; $i < count($words); $i++) {
            $word = $words[$i];
            $cleanWord = preg_replace('/[^a-zA-Z]/', '', $word);
            
            if (empty($cleanWord)) continue;

            // Check if this word is a negation word
            $isNegation = in_array($cleanWord, $negationWords);
            
            // Check if next word exists and is a sentiment word
            $nextWord = null;
            $nextCleanWord = null;
            if ($i + 1 < count($words)) {
                $nextWord = $words[$i + 1];
                $nextCleanWord = preg_replace('/[^a-zA-Z]/', '', $nextWord);
            }

            $wordScore = 0;
            $wordType = null;
            $isNegated = false;

            // Check if current word is a sentiment word
            if (isset($dbWords['positive'][$cleanWord])) {
                $wordScore = $dbWords['positive'][$cleanWord];
                $wordType = 'positive';
                $positiveScore += $wordScore;
            } elseif (isset($dbWords['negative'][$cleanWord])) {
                $wordScore = $dbWords['negative'][$cleanWord];
                $wordType = 'negative';
                $negativeScore += $wordScore;
            } elseif (isset($dbWords['neutral'][$cleanWord])) {
                $wordScore = $dbWords['neutral'][$cleanWord];
                $wordType = 'neutral';
                $neutralScore += $wordScore;
            } else {
                // Fallback to hardcoded words with new scoring system
                if (in_array($cleanWord, $this->positiveWords)) {
                    // Use specific scores for key words
                    if ($cleanWord === 'excellent') {
                        $wordScore = 5.0;
                    } elseif ($cleanWord === 'great') {
                        $wordScore = 3.0;
                    } elseif ($cleanWord === 'good') {
                        $wordScore = 1.0;
                    } else {
                        $wordScore = 2.0; // Default for other positive words
                    }
                    $wordType = 'positive';
                    $positiveScore += $wordScore;
                } elseif (in_array($cleanWord, $this->negativeWords)) {
                    // Use specific scores for key words
                    if ($cleanWord === 'terrible') {
                        $wordScore = -5.0;
                    } elseif ($cleanWord === 'bad') {
                        $wordScore = -3.0;
                    } elseif ($cleanWord === 'poor') {
                        $wordScore = -1.0;
                    } else {
                        $wordScore = -2.0; // Default for other negative words
                    }
                    $wordType = 'negative';
                    $negativeScore += $wordScore;
                } elseif (in_array($cleanWord, $this->neutralWords)) {
                    // Use specific score for okay
                    if ($cleanWord === 'okay') {
                        $wordScore = 0.0;
                    } else {
                        $wordScore = 0.5; // Default for other neutral words
                    }
                    $wordType = 'neutral';
                    $neutralScore += $wordScore;
                }
            }

            // Handle negation
            if ($isNegation && $nextCleanWord && !empty($nextCleanWord)) {
                // Check if next word is a sentiment word
                $nextWordScore = 0;
                $nextWordType = null;
                
                if (isset($dbWords['positive'][$nextCleanWord])) {
                    $nextWordScore = $dbWords['positive'][$nextCleanWord];
                    $nextWordType = 'positive';
                } elseif (isset($dbWords['negative'][$nextCleanWord])) {
                    $nextWordScore = $dbWords['negative'][$nextCleanWord];
                    $nextWordType = 'negative';
                } elseif (isset($dbWords['neutral'][$nextCleanWord])) {
                    $nextWordScore = $dbWords['neutral'][$nextCleanWord];
                    $nextWordType = 'neutral';
                } else {
                    // Fallback to hardcoded words with new scoring system
                    if (in_array($nextCleanWord, $this->positiveWords)) {
                        // Use specific scores for key words
                        if ($nextCleanWord === 'excellent') {
                            $nextWordScore = 5.0;
                        } elseif ($nextCleanWord === 'great') {
                            $nextWordScore = 3.0;
                        } elseif ($nextCleanWord === 'good') {
                            $nextWordScore = 1.0;
                        } else {
                            $nextWordScore = 2.0; // Default for other positive words
                        }
                        $nextWordType = 'positive';
                    } elseif (in_array($nextCleanWord, $this->negativeWords)) {
                        // Use specific scores for key words
                        if ($nextCleanWord === 'terrible') {
                            $nextWordScore = -5.0;
                        } elseif ($nextCleanWord === 'bad') {
                            $nextWordScore = -3.0;
                        } elseif ($nextCleanWord === 'poor') {
                            $nextWordScore = -1.0;
                        } else {
                            $nextWordScore = -2.0; // Default for other negative words
                        }
                        $nextWordType = 'negative';
                    } elseif (in_array($nextCleanWord, $this->neutralWords)) {
                        // Use specific score for okay
                        if ($nextCleanWord === 'okay') {
                            $nextWordScore = 0.0;
                        } else {
                            $nextWordScore = 0.5; // Default for other neutral words
                        }
                        $nextWordType = 'neutral';
                    }
                }

                if ($nextWordScore != 0) {
                    // Negate the score
                    $negatedScore = -$nextWordScore;
                    $score += $negatedScore;
                    
                    // Update scores based on negated type
                    if ($nextWordType === 'positive') {
                        $negativeScore += abs($negatedScore);
                    } elseif ($nextWordType === 'negative') {
                        $positiveScore += abs($negatedScore);
                    }
                    
                    $matchedWords[] = [
                        'word' => $cleanWord . ' ' . $nextCleanWord,
                        'score' => $negatedScore,
                        'type' => $nextWordType === 'positive' ? 'negative' : ($nextWordType === 'negative' ? 'positive' : 'neutral'),
                        'original_type' => $nextWordType,
                        'negated' => true
                    ];
                    
                    // Skip the next word since we've already processed it
                    $i++;
                    continue;
                }
            }

            if ($wordScore != 0) {
                $score += $wordScore;
                $matchedWords[] = [
                    'word' => $cleanWord,
                    'score' => $wordScore,
                    'type' => $wordType,
                    'negated' => false
                ];
            }
        }

        // Determine sentiment
        $sentiment = $this->determineSentiment($score, $positiveScore, $negativeScore);
        
        // Calculate rating (1-5 scale)
        $maxScore = 10;
        $rating = max(1, min(5, 3 + ($score / $maxScore) * 2));

        return [
            'sentiment' => $sentiment,
            'score' => $score,
            'positive_score' => $positiveScore,
            'negative_score' => $negativeScore,
            'neutral_score' => $neutralScore,
            'rating' => round($rating, 1),
            'matched_words' => $matchedWords,
            'original_text' => $text
        ];
    }

    /**
     * Analyze sentiment with translation support
     */
    public function analyzeSentimentWithTranslation(string $text, string $sourceLang = 'tl', string $targetLang = 'en'): array
    {
        $originalText = $text;
        $translatedText = null;
        $translationSuccess = false;

        // Try to translate if source language is not English
        if ($sourceLang !== 'en') {
            $translatedText = $this->translateText($text, $sourceLang, $targetLang);
            $translationSuccess = !is_null($translatedText);
            
            if ($translationSuccess) {
                $analysis = $this->analyzeSentimentWithScore($translatedText, $targetLang);
                $analysis['original_text'] = $originalText;
                $analysis['translated_text'] = $translatedText;
                $analysis['translation_success'] = $translationSuccess;
                return $analysis;
            }
        }

        // If translation failed or not needed, analyze original text
        $analysis = $this->analyzeSentimentWithScore($text, $sourceLang);
        $analysis['original_text'] = $originalText;
        $analysis['translated_text'] = $translatedText;
        $analysis['translation_success'] = $translationSuccess;
        
        return $analysis;
    }

    /**
     * Determine sentiment based on scores
     */
    private function determineSentiment(float $totalScore, float $positiveScore, float $negativeScore): string
    {
        if ($positiveScore > $negativeScore && $positiveScore > 0) {
            return 'positive';
        } elseif ($negativeScore > $positiveScore && $negativeScore > 0) {
            return 'negative';
        } else {
            return 'neutral';
        }
    }

    /**
     * Analyze sentiment based on text input (legacy method)
     */
    public function analyzeSentiment(string $text): string
    {
        $result = $this->analyzeSentimentWithScore($text);
        return $result['sentiment'];
    }

    /**
     * Analyze sentiment from multiple text inputs
     */
    public function analyzeMultipleTexts(array $texts): string
    {
        $positiveCount = 0;
        $negativeCount = 0;
        $neutralCount = 0;

        foreach ($texts as $text) {
            $sentiment = $this->analyzeSentiment($text);
            
            switch ($sentiment) {
                case 'positive':
                    $positiveCount++;
                    break;
                case 'negative':
                    $negativeCount++;
                    break;
                case 'neutral':
                    $neutralCount++;
                    break;
            }
        }

        // Return the most common sentiment
        if ($positiveCount > $negativeCount && $positiveCount > $neutralCount) {
            return 'positive';
        } elseif ($negativeCount > $positiveCount && $negativeCount > $neutralCount) {
            return 'negative';
        } else {
            return 'neutral';
        }
    }

    /**
     * Get sentiment statistics
     */
    public function getSentimentStats(array $texts): array
    {
        $positiveCount = 0;
        $negativeCount = 0;
        $neutralCount = 0;

        foreach ($texts as $text) {
            $sentiment = $this->analyzeSentiment($text);
            
            switch ($sentiment) {
                case 'positive':
                    $positiveCount++;
                    break;
                case 'negative':
                    $negativeCount++;
                    break;
                case 'neutral':
                    $neutralCount++;
                    break;
            }
        }

        $total = count($texts);
        
        return [
            'positive' => [
                'count' => $positiveCount,
                'percentage' => $total > 0 ? round(($positiveCount / $total) * 100, 2) : 0
            ],
            'negative' => [
                'count' => $negativeCount,
                'percentage' => $total > 0 ? round(($negativeCount / $total) * 100, 2) : 0
            ],
            'neutral' => [
                'count' => $neutralCount,
                'percentage' => $total > 0 ? round(($neutralCount / $total) * 100, 2) : 0
            ],
            'total' => $total
        ];
    }

    /**
     * Add new sentiment word to database
     */
    public function addSentimentWord(string $word, string $type, float $score, string $language = 'en'): bool
    {
        try {
            SentimentWord::create([
                'word' => strtolower($word),
                'type' => $type,
                'score' => $score,
                'language' => $language,
                'is_active' => true
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('Error adding sentiment word: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update sentiment word score
     */
    public function updateSentimentWordScore(string $word, float $score, string $language = 'en'): bool
    {
        try {
            SentimentWord::where('word', strtolower($word))
                        ->where('language', $language)
                        ->update(['score' => $score]);
            return true;
        } catch (\Exception $e) {
            Log::error('Error updating sentiment word score: ' . $e->getMessage());
            return false;
        }
    }
} 