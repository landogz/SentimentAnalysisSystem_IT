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
        // Clear existing sentiment words
        SentimentWord::query()->delete();
        $this->command->info('Cleared existing sentiment words...');

        // English positive words with scores and negations
        $englishPositiveWords = [
            'excellent' => ['score' => 5.0, 'negation' => 'terrible'],
            'great' => ['score' => 3.0, 'negation' => 'awful'],
            'amazing' => ['score' => 3.0, 'negation' => 'disappointing'],
            'outstanding' => ['score' => 3.0, 'negation' => 'poor'],
            'fantastic' => ['score' => 2.5, 'negation' => 'terrible'],
            'wonderful' => ['score' => 2.5, 'negation' => 'horrible'],
            'brilliant' => ['score' => 3.0, 'negation' => 'stupid'],
            'superb' => ['score' => 3.0, 'negation' => 'inferior'],
            'perfect' => ['score' => 3.0, 'negation' => 'imperfect'],
            'awesome' => ['score' => 2.5, 'negation' => 'awful'],
            'incredible' => ['score' => 3.0, 'negation' => 'ordinary'],
            'terrific' => ['score' => 2.5, 'negation' => 'terrible'],
            'helpful' => ['score' => 2.0, 'negation' => 'unhelpful'],
            'knowledgeable' => ['score' => 2.0, 'negation' => 'ignorant'],
            'patient' => ['score' => 1.5, 'negation' => 'impatient'],
            'clear' => ['score' => 1.5, 'negation' => 'unclear'],
            'organized' => ['score' => 1.5, 'negation' => 'disorganized'],
            'engaging' => ['score' => 2.0, 'negation' => 'boring'],
            'inspiring' => ['score' => 2.5, 'negation' => 'discouraging'],
            'motivating' => ['score' => 2.0, 'negation' => 'demotivating'],
            'professional' => ['score' => 2.0, 'negation' => 'unprofessional'],
            'dedicated' => ['score' => 2.0, 'negation' => 'lazy'],
            'caring' => ['score' => 2.0, 'negation' => 'uncaring'],
            'supportive' => ['score' => 2.0, 'negation' => 'unsupportive'],
            'enthusiastic' => ['score' => 2.0, 'negation' => 'unenthusiastic'],
            'passionate' => ['score' => 2.0, 'negation' => 'apathetic'],
            'creative' => ['score' => 2.0, 'negation' => 'uncreative'],
            'innovative' => ['score' => 2.0, 'negation' => 'conventional'],
            'effective' => ['score' => 2.0, 'negation' => 'ineffective'],
            'efficient' => ['score' => 1.5, 'negation' => 'inefficient'],
            'thorough' => ['score' => 1.5, 'negation' => 'superficial'],
            'detailed' => ['score' => 1.5, 'negation' => 'vague'],
            'comprehensive' => ['score' => 2.0, 'negation' => 'incomplete'],
            'well-prepared' => ['score' => 2.0, 'negation' => 'unprepared'],
            'punctual' => ['score' => 1.5, 'negation' => 'late'],
            'reliable' => ['score' => 2.0, 'negation' => 'unreliable'],
            'love' => ['score' => 3.0, 'negation' => 'hate'],
            'happy' => ['score' => 2.0, 'negation' => 'sad'],
            'satisfied' => ['score' => 2.0, 'negation' => 'dissatisfied'],
            'good' => ['score' => 1.0, 'negation' => 'bad'],
            'beautiful' => ['score' => 3.0, 'negation' => 'ugly'],
            'smart' => ['score' => 2.0, 'negation' => 'stupid'],
        ];

        // English negative words with scores and negations
        $englishNegativeWords = [
            'poor' => ['score' => -1.0, 'negation' => 'excellent'],
            'terrible' => ['score' => -5.0, 'negation' => 'excellent'],
            'awful' => ['score' => -3.0, 'negation' => 'great'],
            'horrible' => ['score' => -3.0, 'negation' => 'wonderful'],
            'bad' => ['score' => -3.0, 'negation' => 'good'],
            'worst' => ['score' => -3.0, 'negation' => 'best'],
            'disappointing' => ['score' => -2.5, 'negation' => 'amazing'],
            'confusing' => ['score' => -2.0, 'negation' => 'clear'],
            'unclear' => ['score' => -2.0, 'negation' => 'clear'],
            'disorganized' => ['score' => -2.0, 'negation' => 'organized'],
            'boring' => ['score' => -2.0, 'negation' => 'engaging'],
            'monotone' => ['score' => -1.5, 'negation' => 'engaging'],
            'unprepared' => ['score' => -2.0, 'negation' => 'well-prepared'],
            'late' => ['score' => -1.5, 'negation' => 'punctual'],
            'unreliable' => ['score' => -2.0, 'negation' => 'reliable'],
            'unprofessional' => ['score' => -2.5, 'negation' => 'professional'],
            'rude' => ['score' => -2.5, 'negation' => 'polite'],
            'impatient' => ['score' => -2.0, 'negation' => 'patient'],
            'unhelpful' => ['score' => -2.0, 'negation' => 'helpful'],
            'ineffective' => ['score' => -2.0, 'negation' => 'effective'],
            'waste' => ['score' => -2.5, 'negation' => 'valuable'],
            'useless' => ['score' => -2.5, 'negation' => 'useful'],
            'difficult' => ['score' => -1.5, 'negation' => 'easy'],
            'complicated' => ['score' => -1.5, 'negation' => 'simple'],
            'overwhelming' => ['score' => -2.0, 'negation' => 'manageable'],
            'stressful' => ['score' => -2.0, 'negation' => 'relaxing'],
            'frustrating' => ['score' => -2.0, 'negation' => 'satisfying'],
            'annoying' => ['score' => -2.0, 'negation' => 'pleasant'],
            'irritating' => ['score' => -2.0, 'negation' => 'soothing'],
            'unfair' => ['score' => -2.5, 'negation' => 'fair'],
            'biased' => ['score' => -2.5, 'negation' => 'unbiased'],
            'incompetent' => ['score' => -3.0, 'negation' => 'competent'],
            'unqualified' => ['score' => -3.0, 'negation' => 'qualified'],
            'lazy' => ['score' => -2.5, 'negation' => 'dedicated'],
            'careless' => ['score' => -2.0, 'negation' => 'careful'],
            'negligent' => ['score' => -3.0, 'negation' => 'diligent'],
            'hate' => ['score' => -3.0, 'negation' => 'love'],
            'slow' => ['score' => -1.5, 'negation' => 'fast'],
            'ugly' => ['score' => -3.0, 'negation' => 'beautiful'],
            'stupid' => ['score' => -2.0, 'negation' => 'smart'],
            'sad' => ['score' => -2.5, 'negation' => 'happy'],
        ];

        // English neutral words with scores
        $englishNeutralWords = [
            'okay' => ['score' => 0.0, 'negation' => null],
            'fine' => ['score' => 0.5, 'negation' => null],
            'average' => ['score' => 0.0, 'negation' => null],
            'normal' => ['score' => 0.0, 'negation' => null],
            'standard' => ['score' => 0.0, 'negation' => null],
            'regular' => ['score' => 0.0, 'negation' => null],
            'usual' => ['score' => 0.0, 'negation' => null],
            'adequate' => ['score' => 0.5, 'negation' => null],
            'satisfactory' => ['score' => 0.5, 'negation' => null],
            'acceptable' => ['score' => 0.5, 'negation' => null],
            'reasonable' => ['score' => 0.5, 'negation' => null],
            'moderate' => ['score' => 0.0, 'negation' => null],
            'decent' => ['score' => 0.5, 'negation' => null],
            'fair' => ['score' => 0.5, 'negation' => null],
            'balanced' => ['score' => 0.0, 'negation' => null],
            'neutral' => ['score' => 0.0, 'negation' => null],
            'indifferent' => ['score' => 0.0, 'negation' => null],
            'mixed' => ['score' => 0.0, 'negation' => null],
        ];

        // Tagalog positive words with scores and negations
        $tagalogPositiveWords = [
            'maganda' => ['score' => 2.0, 'negation' => 'pangit'],
            'mahusay' => ['score' => 2.5, 'negation' => 'hindi-mahusay'],
            'napakaganda' => ['score' => 3.0, 'negation' => 'napakapangit'],
            'napakahusay' => ['score' => 3.0, 'negation' => 'napakasama'],
            'mabuti' => ['score' => 2.0, 'negation' => 'masama'],
            'masaya' => ['score' => 2.0, 'negation' => 'malungkot'],
            'matalino' => ['score' => 2.0, 'negation' => 'bobo'],
            'magaling' => ['score' => 2.5, 'negation' => 'hindi-magaling'],
            'gusto' => ['score' => 2.0, 'negation' => 'ayaw'],
            'mahal' => ['score' => 2.5, 'negation' => 'hindi-mahal'],
            'kontento' => ['score' => 2.0, 'negation' => 'hindi-kontento'],
        ];

        // Tagalog negative words with scores and negations
        $tagalogNegativeWords = [
            'masama' => ['score' => -2.0, 'negation' => 'mabuti'],
            'pangit' => ['score' => -2.0, 'negation' => 'maganda'],
            'napakasama' => ['score' => -3.0, 'negation' => 'napakahusay'],
            'napakapangit' => ['score' => -3.0, 'negation' => 'napakaganda'],
            'malungkot' => ['score' => -2.0, 'negation' => 'masaya'],
            'bobo' => ['score' => -2.0, 'negation' => 'matalino'],
            'hindi-magaling' => ['score' => -2.5, 'negation' => 'magaling'],
            'hindi-mahusay' => ['score' => -2.5, 'negation' => 'mahusay'],
            'mabagal' => ['score' => -1.5, 'negation' => 'mabilis'],
            'ayaw' => ['score' => -2.0, 'negation' => 'gusto'],
            'hindi' => ['score' => -1.0, 'negation' => 'oo'],
            'hindi-mahal' => ['score' => -2.5, 'negation' => 'mahal'],
            'hindi-kontento' => ['score' => -2.0, 'negation' => 'kontento'],
        ];

        // Tagalog neutral words with scores
        $tagalogNeutralWords = [
            'sige' => ['score' => 0.5, 'negation' => null],
            'pwede' => ['score' => 0.5, 'negation' => null],
            'okay' => ['score' => 0.0, 'negation' => null],
            'fine' => ['score' => 0.5, 'negation' => null],
            'average' => ['score' => 0.0, 'negation' => null],
            'normal' => ['score' => 0.0, 'negation' => null],
            'standard' => ['score' => 0.0, 'negation' => null],
            'regular' => ['score' => 0.0, 'negation' => null],
            'usual' => ['score' => 0.0, 'negation' => null],
            'adequate' => ['score' => 0.5, 'negation' => null],
            'satisfactory' => ['score' => 0.5, 'negation' => null],
            'acceptable' => ['score' => 0.5, 'negation' => null],
            'reasonable' => ['score' => 0.5, 'negation' => null],
            'moderate' => ['score' => 0.0, 'negation' => null],
            'decent' => ['score' => 0.5, 'negation' => null],
            'fair' => ['score' => 0.5, 'negation' => null],
            'balanced' => ['score' => 0.0, 'negation' => null],
            'neutral' => ['score' => 0.0, 'negation' => null],
            'indifferent' => ['score' => 0.0, 'negation' => null],
            'mixed' => ['score' => 0.0, 'negation' => null],
        ];

        // Insert English words
        foreach ($englishPositiveWords as $word => $data) {
            SentimentWord::create([
                'word' => $word,
                'negation' => $data['negation'],
                'type' => 'positive',
                'score' => $data['score'],
                'language' => 'en',
                'is_active' => true
            ]);
        }

        foreach ($englishNegativeWords as $word => $data) {
            SentimentWord::create([
                'word' => $word,
                'negation' => $data['negation'],
                'type' => 'negative',
                'score' => $data['score'],
                'language' => 'en',
                'is_active' => true
            ]);
        }

        foreach ($englishNeutralWords as $word => $data) {
            SentimentWord::create([
                'word' => $word,
                'negation' => $data['negation'],
                'type' => 'neutral',
                'score' => $data['score'],
                'language' => 'en',
                'is_active' => true
            ]);
        }

        // Insert Tagalog words
        foreach ($tagalogPositiveWords as $word => $data) {
            SentimentWord::create([
                'word' => $word,
                'negation' => $data['negation'],
                'type' => 'positive',
                'score' => $data['score'],
                'language' => 'tl',
                'is_active' => true
            ]);
        }

        foreach ($tagalogNegativeWords as $word => $data) {
            SentimentWord::create([
                'word' => $word,
                'negation' => $data['negation'],
                'type' => 'negative',
                'score' => $data['score'],
                'language' => 'tl',
                'is_active' => true
            ]);
        }

        foreach ($tagalogNeutralWords as $word => $data) {
            SentimentWord::create([
                'word' => $word,
                'negation' => $data['negation'],
                'type' => 'neutral',
                'score' => $data['score'],
                'language' => 'tl',
                'is_active' => true
            ]);
        }

        $totalWords = count($englishPositiveWords) + count($englishNegativeWords) + count($englishNeutralWords) +
                     count($tagalogPositiveWords) + count($tagalogNegativeWords) + count($tagalogNeutralWords);

        $this->command->info("Sentiment words seeded successfully!");
        $this->command->info("Total words added: $totalWords");
        $this->command->info("English words: " . (count($englishPositiveWords) + count($englishNegativeWords) + count($englishNeutralWords)));
        $this->command->info("Tagalog words: " . (count($tagalogPositiveWords) + count($tagalogNegativeWords) + count($tagalogNeutralWords)));
        $this->command->info("Words with negation pairs: " . (count($englishPositiveWords) + count($englishNegativeWords) + count($tagalogPositiveWords) + count($tagalogNegativeWords)));
    }
} 