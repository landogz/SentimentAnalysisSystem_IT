<?php
/**
 * Example Sentiment Analysis with Translation and Scoring
 * 
 * This script demonstrates the enhanced sentiment analysis system that includes:
 * - Database-driven sentiment words with scores
 * - Translation support (Tagalog to English)
 * - Advanced scoring system
 * - Rating calculation
 */

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\SentimentAnalysisService;
use App\Models\SentimentWord;

// Initialize the service
$sentimentService = new SentimentAnalysisService();

echo "=== Enhanced Sentiment Analysis System ===\n\n";

// Example 1: English text analysis
echo "1. English Text Analysis:\n";
$englishText = "I love this teacher, they are excellent and very helpful!";
$englishResult = $sentimentService->analyzeSentimentWithScore($englishText, 'en');

echo "Original Text: {$englishResult['original_text']}\n";
echo "Sentiment: {$englishResult['sentiment']}\n";
echo "Score: {$englishResult['score']}\n";
echo "Rating: {$englishResult['rating']}/5\n";
echo "Positive Score: {$englishResult['positive_score']}\n";
echo "Negative Score: {$englishResult['negative_score']}\n";
echo "Neutral Score: {$englishResult['neutral_score']}\n";

if (!empty($englishResult['matched_words'])) {
    echo "Matched Words:\n";
    foreach ($englishResult['matched_words'] as $match) {
        echo "  - {$match['word']} ({$match['score']}) [{$match['type']}]\n";
    }
}
echo "\n";

// Example 2: Tagalog text analysis (without translation)
echo "2. Tagalog Text Analysis (Direct):\n";
$tagalogText = "Gusto ko ang serbisyo, pero mabagal ang delivery.";
$tagalogResult = $sentimentService->analyzeSentimentWithScore($tagalogText, 'tl');

echo "Original Text: {$tagalogResult['original_text']}\n";
echo "Sentiment: {$tagalogResult['sentiment']}\n";
echo "Score: {$tagalogResult['score']}\n";
echo "Rating: {$tagalogResult['rating']}/5\n";
echo "Positive Score: {$tagalogResult['positive_score']}\n";
echo "Negative Score: {$tagalogResult['negative_score']}\n";
echo "Neutral Score: {$tagalogResult['neutral_score']}\n";

if (!empty($tagalogResult['matched_words'])) {
    echo "Matched Words:\n";
    foreach ($tagalogResult['matched_words'] as $match) {
        echo "  - {$match['word']} ({$match['score']}) [{$match['type']}]\n";
    }
}
echo "\n";

// Example 3: Tagalog text with translation
echo "3. Tagalog Text Analysis (with Translation):\n";
$translationResult = $sentimentService->analyzeSentimentWithTranslation($tagalogText, 'tl', 'en');

echo "Original Text: {$translationResult['original_text']}\n";
if ($translationResult['translation_success']) {
    echo "Translated Text: {$translationResult['translated_text']}\n";
} else {
    echo "Translation: Failed (API key not configured)\n";
}
echo "Sentiment: {$translationResult['sentiment']}\n";
echo "Score: {$translationResult['score']}\n";
echo "Rating: {$translationResult['rating']}/5\n";
echo "Positive Score: {$translationResult['positive_score']}\n";
echo "Negative Score: {$translationResult['negative_score']}\n";
echo "Neutral Score: {$translationResult['neutral_score']}\n";

if (!empty($translationResult['matched_words'])) {
    echo "Matched Words:\n";
    foreach ($translationResult['matched_words'] as $match) {
        echo "  - {$match['word']} ({$match['score']}) [{$match['type']}]\n";
    }
}
echo "\n";

// Example 4: Database statistics
echo "4. Database Statistics:\n";
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

echo "Total Words: {$stats['total']}\n";
echo "Positive Words: {$stats['by_type']['positive']}\n";
echo "Negative Words: {$stats['by_type']['negative']}\n";
echo "Neutral Words: {$stats['by_type']['neutral']}\n";
echo "Active Words: {$stats['active']}\n";
echo "Inactive Words: {$stats['inactive']}\n";

foreach ($stats['by_language'] as $lang => $count) {
    echo "Words in {$lang}: {$count}\n";
}
echo "\n";

// Example 5: Sample words from database
echo "5. Sample Words from Database:\n";
$sampleWords = SentimentWord::where('is_active', true)
    ->orderBy('type')
    ->orderBy('word')
    ->limit(10)
    ->get();

foreach ($sampleWords as $word) {
    $typeColor = match($word->type) {
        'positive' => 'green',
        'negative' => 'red',
        'neutral' => 'yellow'
    };
    echo "  - {$word->word} ({$word->score}) [{$word->type}] [{$word->language}]\n";
}
echo "\n";

// Example 6: Adding a new word
echo "6. Adding a New Word:\n";
$newWord = "fantastic";
$newWordType = "positive";
$newWordScore = 3.0;
$newWordLanguage = "en";

$success = $sentimentService->addSentimentWord($newWord, $newWordType, $newWordScore, $newWordLanguage);
if ($success) {
    echo "Successfully added '{$newWord}' with score {$newWordScore}\n";
} else {
    echo "Failed to add '{$newWord}'\n";
}

// Test the new word
$testResult = $sentimentService->analyzeSentimentWithScore("This is a fantastic experience!", 'en');
echo "Test with new word: Sentiment = {$testResult['sentiment']}, Score = {$testResult['score']}\n";
echo "\n";

echo "=== End of Example ===\n"; 