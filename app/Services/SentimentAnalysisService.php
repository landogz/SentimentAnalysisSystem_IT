<?php

namespace App\Services;

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
     * Analyze sentiment based on text input
     */
    public function analyzeSentiment(string $text): string
    {
        $text = strtolower(trim($text));
        $words = preg_split('/\s+/', $text);
        
        $positiveScore = 0;
        $negativeScore = 0;
        $neutralScore = 0;

        foreach ($words as $word) {
            $word = preg_replace('/[^a-zA-Z]/', '', $word);
            
            if (in_array($word, $this->positiveWords)) {
                $positiveScore += 2; // Give more weight to positive words
            } elseif (in_array($word, $this->negativeWords)) {
                $negativeScore += 2; // Give more weight to negative words
            } elseif (in_array($word, $this->neutralWords)) {
                $neutralScore += 1;
            }
        }

        // Determine sentiment based on scores
        if ($positiveScore > $negativeScore && $positiveScore > 0) {
            return 'positive';
        } elseif ($negativeScore > $positiveScore && $negativeScore > 0) {
            return 'negative';
        } else {
            return 'neutral';
        }
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
} 