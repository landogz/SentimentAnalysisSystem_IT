<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SentimentWord;

class SentimentWordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // English positive words with scores
        $englishPositiveWords = [
            'excellent' => 3.0, 'great' => 2.5, 'amazing' => 3.0, 'outstanding' => 3.0,
            'fantastic' => 2.5, 'wonderful' => 2.5, 'brilliant' => 3.0, 'superb' => 3.0,
            'perfect' => 3.0, 'awesome' => 2.5, 'incredible' => 3.0, 'terrific' => 2.5,
            'helpful' => 2.0, 'knowledgeable' => 2.0, 'patient' => 1.5, 'clear' => 1.5,
            'organized' => 1.5, 'engaging' => 2.0, 'inspiring' => 2.5, 'motivating' => 2.0,
            'professional' => 2.0, 'dedicated' => 2.0, 'caring' => 2.0, 'supportive' => 2.0,
            'enthusiastic' => 2.0, 'passionate' => 2.0, 'creative' => 2.0, 'innovative' => 2.0,
            'effective' => 2.0, 'efficient' => 1.5, 'thorough' => 1.5, 'detailed' => 1.5,
            'comprehensive' => 2.0, 'well-prepared' => 2.0, 'punctual' => 1.5, 'reliable' => 2.0,
            'love' => 3.0, 'happy' => 2.0, 'satisfied' => 2.0, 'good' => 1.5
        ];

        // English negative words with scores
        $englishNegativeWords = [
            'poor' => -2.0, 'terrible' => -3.0, 'awful' => -3.0, 'horrible' => -3.0,
            'bad' => -2.0, 'worst' => -3.0, 'disappointing' => -2.5, 'confusing' => -2.0,
            'unclear' => -2.0, 'disorganized' => -2.0, 'boring' => -2.0, 'monotone' => -1.5,
            'unprepared' => -2.0, 'late' => -1.5, 'unreliable' => -2.0, 'unprofessional' => -2.5,
            'rude' => -2.5, 'impatient' => -2.0, 'unhelpful' => -2.0, 'ineffective' => -2.0,
            'waste' => -2.5, 'useless' => -2.5, 'difficult' => -1.5, 'complicated' => -1.5,
            'overwhelming' => -2.0, 'stressful' => -2.0, 'frustrating' => -2.0, 'annoying' => -2.0,
            'irritating' => -2.0, 'unfair' => -2.5, 'biased' => -2.5, 'incompetent' => -3.0,
            'unqualified' => -3.0, 'lazy' => -2.5, 'careless' => -2.0, 'negligent' => -3.0,
            'hate' => -3.0, 'slow' => -1.5
        ];

        // English neutral words with scores
        $englishNeutralWords = [
            'okay' => 0.5, 'fine' => 0.5, 'average' => 0.0, 'normal' => 0.0,
            'standard' => 0.0, 'regular' => 0.0, 'usual' => 0.0, 'adequate' => 0.5,
            'satisfactory' => 0.5, 'acceptable' => 0.5, 'reasonable' => 0.5, 'moderate' => 0.0,
            'decent' => 0.5, 'fair' => 0.5, 'balanced' => 0.0, 'neutral' => 0.0,
            'indifferent' => 0.0, 'mixed' => 0.0
        ];

        // Tagalog positive words with scores
        $tagalogPositiveWords = [
            'maganda' => 2.0, 'mahusay' => 2.5, 'napakaganda' => 3.0, 'napakahusay' => 3.0,
            'excellent' => 3.0, 'great' => 2.5, 'amazing' => 3.0, 'outstanding' => 3.0,
            'fantastic' => 2.5, 'wonderful' => 2.5, 'brilliant' => 3.0, 'superb' => 3.0,
            'perfect' => 3.0, 'awesome' => 2.5, 'incredible' => 3.0, 'terrific' => 2.5,
            'helpful' => 2.0, 'knowledgeable' => 2.0, 'patient' => 1.5, 'clear' => 1.5,
            'organized' => 1.5, 'engaging' => 2.0, 'inspiring' => 2.5, 'motivating' => 2.0,
            'professional' => 2.0, 'dedicated' => 2.0, 'caring' => 2.0, 'supportive' => 2.0,
            'enthusiastic' => 2.0, 'passionate' => 2.0, 'creative' => 2.0, 'innovative' => 2.0,
            'effective' => 2.0, 'efficient' => 1.5, 'thorough' => 1.5, 'detailed' => 1.5,
            'comprehensive' => 2.0, 'well-prepared' => 2.0, 'punctual' => 1.5, 'reliable' => 2.0,
            'love' => 3.0, 'happy' => 2.0, 'satisfied' => 2.0, 'good' => 1.5,
            'gusto' => 2.0, 'mahal' => 2.5, 'masaya' => 2.0, 'kontento' => 2.0
        ];

        // Tagalog negative words with scores
        $tagalogNegativeWords = [
            'masama' => -2.0, 'pangit' => -2.0, 'napakasama' => -3.0, 'napakapangit' => -3.0,
            'poor' => -2.0, 'terrible' => -3.0, 'awful' => -3.0, 'horrible' => -3.0,
            'bad' => -2.0, 'worst' => -3.0, 'disappointing' => -2.5, 'confusing' => -2.0,
            'unclear' => -2.0, 'disorganized' => -2.0, 'boring' => -2.0, 'monotone' => -1.5,
            'unprepared' => -2.0, 'late' => -1.5, 'unreliable' => -2.0, 'unprofessional' => -2.5,
            'rude' => -2.5, 'impatient' => -2.0, 'unhelpful' => -2.0, 'ineffective' => -2.0,
            'waste' => -2.5, 'useless' => -2.5, 'difficult' => -1.5, 'complicated' => -1.5,
            'overwhelming' => -2.0, 'stressful' => -2.0, 'frustrating' => -2.0, 'annoying' => -2.0,
            'irritating' => -2.0, 'unfair' => -2.5, 'biased' => -2.5, 'incompetent' => -3.0,
            'unqualified' => -3.0, 'lazy' => -2.5, 'careless' => -2.0, 'negligent' => -3.0,
            'hate' => -3.0, 'slow' => -1.5, 'mabagal' => -1.5, 'ayaw' => -2.0, 'hindi' => -1.0
        ];

        // Tagalog neutral words with scores
        $tagalogNeutralWords = [
            'okay' => 0.5, 'fine' => 0.5, 'average' => 0.0, 'normal' => 0.0,
            'standard' => 0.0, 'regular' => 0.0, 'usual' => 0.0, 'adequate' => 0.5,
            'satisfactory' => 0.5, 'acceptable' => 0.5, 'reasonable' => 0.5, 'moderate' => 0.0,
            'decent' => 0.5, 'fair' => 0.5, 'balanced' => 0.0, 'neutral' => 0.0,
            'indifferent' => 0.0, 'mixed' => 0.0, 'sige' => 0.5, 'pwede' => 0.5
        ];

        // Insert English words
        foreach ($englishPositiveWords as $word => $score) {
            SentimentWord::create([
                'word' => $word,
                'type' => 'positive',
                'score' => $score,
                'language' => 'en',
                'is_active' => true
            ]);
        }

        foreach ($englishNegativeWords as $word => $score) {
            SentimentWord::create([
                'word' => $word,
                'type' => 'negative',
                'score' => $score,
                'language' => 'en',
                'is_active' => true
            ]);
        }

        foreach ($englishNeutralWords as $word => $score) {
            SentimentWord::create([
                'word' => $word,
                'type' => 'neutral',
                'score' => $score,
                'language' => 'en',
                'is_active' => true
            ]);
        }

        // Insert Tagalog words
        foreach ($tagalogPositiveWords as $word => $score) {
            SentimentWord::create([
                'word' => $word,
                'type' => 'positive',
                'score' => $score,
                'language' => 'tl',
                'is_active' => true
            ]);
        }

        foreach ($tagalogNegativeWords as $word => $score) {
            SentimentWord::create([
                'word' => $word,
                'type' => 'negative',
                'score' => $score,
                'language' => 'tl',
                'is_active' => true
            ]);
        }

        foreach ($tagalogNeutralWords as $word => $score) {
            SentimentWord::create([
                'word' => $word,
                'type' => 'neutral',
                'score' => $score,
                'language' => 'tl',
                'is_active' => true
            ]);
        }

        $this->command->info('Sentiment words seeded successfully!');
    }
} 