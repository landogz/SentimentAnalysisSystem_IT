<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SentimentWord;

class NegationPairsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Adding negation pairs...');

        // English negation pairs
        $englishPairs = [
            // Positive -> Negative
            ['word' => 'beautiful', 'negation' => 'ugly', 'type' => 'positive', 'score' => 3.0],
            ['word' => 'ugly', 'negation' => 'beautiful', 'type' => 'negative', 'score' => -3.0],
            
            ['word' => 'good', 'negation' => 'bad', 'type' => 'positive', 'score' => 2.0],
            ['word' => 'bad', 'negation' => 'good', 'type' => 'negative', 'score' => -2.0],
            
            ['word' => 'excellent', 'negation' => 'terrible', 'type' => 'positive', 'score' => 3.5],
            ['word' => 'terrible', 'negation' => 'excellent', 'type' => 'negative', 'score' => -3.5],
            
            ['word' => 'happy', 'negation' => 'sad', 'type' => 'positive', 'score' => 2.5],
            ['word' => 'sad', 'negation' => 'happy', 'type' => 'negative', 'score' => -2.5],
            
            ['word' => 'smart', 'negation' => 'stupid', 'type' => 'positive', 'score' => 2.0],
            ['word' => 'stupid', 'negation' => 'smart', 'type' => 'negative', 'score' => -2.0],
            
            ['word' => 'great', 'negation' => 'awful', 'type' => 'positive', 'score' => 2.5],
            ['word' => 'awful', 'negation' => 'great', 'type' => 'negative', 'score' => -2.5],
            
            ['word' => 'wonderful', 'negation' => 'horrible', 'type' => 'positive', 'score' => 3.0],
            ['word' => 'horrible', 'negation' => 'wonderful', 'type' => 'negative', 'score' => -3.0],
            
            ['word' => 'amazing', 'negation' => 'disappointing', 'type' => 'positive', 'score' => 3.0],
            ['word' => 'disappointing', 'negation' => 'amazing', 'type' => 'negative', 'score' => -3.0],
            
            ['word' => 'perfect', 'negation' => 'imperfect', 'type' => 'positive', 'score' => 3.5],
            ['word' => 'imperfect', 'negation' => 'perfect', 'type' => 'negative', 'score' => -2.0],
            
            ['word' => 'helpful', 'negation' => 'unhelpful', 'type' => 'positive', 'score' => 2.0],
            ['word' => 'unhelpful', 'negation' => 'helpful', 'type' => 'negative', 'score' => -2.0],
            
            ['word' => 'knowledgeable', 'negation' => 'ignorant', 'type' => 'positive', 'score' => 2.5],
            ['word' => 'ignorant', 'negation' => 'knowledgeable', 'type' => 'negative', 'score' => -2.5],
            
            ['word' => 'patient', 'negation' => 'impatient', 'type' => 'positive', 'score' => 2.0],
            ['word' => 'impatient', 'negation' => 'patient', 'type' => 'negative', 'score' => -2.0],
            
            ['word' => 'clear', 'negation' => 'unclear', 'type' => 'positive', 'score' => 2.0],
            ['word' => 'unclear', 'negation' => 'clear', 'type' => 'negative', 'score' => -2.0],
            
            ['word' => 'organized', 'negation' => 'disorganized', 'type' => 'positive', 'score' => 2.0],
            ['word' => 'disorganized', 'negation' => 'organized', 'type' => 'negative', 'score' => -2.0],
            
            ['word' => 'engaging', 'negation' => 'boring', 'type' => 'positive', 'score' => 2.5],
            ['word' => 'boring', 'negation' => 'engaging', 'type' => 'negative', 'score' => -2.5],
            
            ['word' => 'inspiring', 'negation' => 'discouraging', 'type' => 'positive', 'score' => 3.0],
            ['word' => 'discouraging', 'negation' => 'inspiring', 'type' => 'negative', 'score' => -3.0],
            
            ['word' => 'professional', 'negation' => 'unprofessional', 'type' => 'positive', 'score' => 2.5],
            ['word' => 'unprofessional', 'negation' => 'professional', 'type' => 'negative', 'score' => -2.5],
            
            ['word' => 'dedicated', 'negation' => 'lazy', 'type' => 'positive', 'score' => 2.5],
            ['word' => 'lazy', 'negation' => 'dedicated', 'type' => 'negative', 'score' => -2.5],
            
            ['word' => 'caring', 'negation' => 'uncaring', 'type' => 'positive', 'score' => 2.5],
            ['word' => 'uncaring', 'negation' => 'caring', 'type' => 'negative', 'score' => -2.5],
            
            ['word' => 'supportive', 'negation' => 'unsupportive', 'type' => 'positive', 'score' => 2.5],
            ['word' => 'unsupportive', 'negation' => 'supportive', 'type' => 'negative', 'score' => -2.5],
            
            ['word' => 'enthusiastic', 'negation' => 'unenthusiastic', 'type' => 'positive', 'score' => 2.5],
            ['word' => 'unenthusiastic', 'negation' => 'enthusiastic', 'type' => 'negative', 'score' => -2.5],
            
            ['word' => 'passionate', 'negation' => 'apathetic', 'type' => 'positive', 'score' => 3.0],
            ['word' => 'apathetic', 'negation' => 'passionate', 'type' => 'negative', 'score' => -3.0],
            
            ['word' => 'creative', 'negation' => 'uncreative', 'type' => 'positive', 'score' => 2.5],
            ['word' => 'uncreative', 'negation' => 'creative', 'type' => 'negative', 'score' => -2.5],
            
            ['word' => 'innovative', 'negation' => 'conventional', 'type' => 'positive', 'score' => 2.5],
            ['word' => 'conventional', 'negation' => 'innovative', 'type' => 'negative', 'score' => -1.5],
            
            ['word' => 'effective', 'negation' => 'ineffective', 'type' => 'positive', 'score' => 2.5],
            ['word' => 'ineffective', 'negation' => 'effective', 'type' => 'negative', 'score' => -2.5],
            
            ['word' => 'efficient', 'negation' => 'inefficient', 'type' => 'positive', 'score' => 2.5],
            ['word' => 'inefficient', 'negation' => 'efficient', 'type' => 'negative', 'score' => -2.5],
            
            ['word' => 'thorough', 'negation' => 'superficial', 'type' => 'positive', 'score' => 2.5],
            ['word' => 'superficial', 'negation' => 'thorough', 'type' => 'negative', 'score' => -2.5],
            
            ['word' => 'detailed', 'negation' => 'vague', 'type' => 'positive', 'score' => 2.0],
            ['word' => 'vague', 'negation' => 'detailed', 'type' => 'negative', 'score' => -2.0],
            
            ['word' => 'comprehensive', 'negation' => 'incomplete', 'type' => 'positive', 'score' => 2.5],
            ['word' => 'incomplete', 'negation' => 'comprehensive', 'type' => 'negative', 'score' => -2.5],
            
            ['word' => 'well-prepared', 'negation' => 'unprepared', 'type' => 'positive', 'score' => 2.5],
            ['word' => 'unprepared', 'negation' => 'well-prepared', 'type' => 'negative', 'score' => -2.5],
            
            ['word' => 'punctual', 'negation' => 'late', 'type' => 'positive', 'score' => 2.0],
            ['word' => 'late', 'negation' => 'punctual', 'type' => 'negative', 'score' => -2.0],
            
            ['word' => 'reliable', 'negation' => 'unreliable', 'type' => 'positive', 'score' => 2.5],
            ['word' => 'unreliable', 'negation' => 'reliable', 'type' => 'negative', 'score' => -2.5],
        ];

        foreach ($englishPairs as $pair) {
            // Check if word already exists
            $existingWord = SentimentWord::where('word', $pair['word'])
                                        ->where('language', 'en')
                                        ->first();
            
            if ($existingWord) {
                // Update existing word with negation
                $existingWord->update([
                    'negation' => $pair['negation']
                ]);
            } else {
                // Create new word
                SentimentWord::create([
                    'word' => $pair['word'],
                    'negation' => $pair['negation'],
                    'type' => $pair['type'],
                    'score' => $pair['score'],
                    'language' => 'en',
                    'is_active' => true
                ]);
            }
        }

        $this->command->info('English negation pairs added successfully!');
        
        // Tagalog negation pairs
        $tagalogPairs = [
            // Positive -> Negative
            ['word' => 'maganda', 'negation' => 'pangit', 'type' => 'positive', 'score' => 3.0],
            ['word' => 'pangit', 'negation' => 'maganda', 'type' => 'negative', 'score' => -3.0],
            
            ['word' => 'mabuti', 'negation' => 'masama', 'type' => 'positive', 'score' => 2.0],
            ['word' => 'masama', 'negation' => 'mabuti', 'type' => 'negative', 'score' => -2.0],
            
            ['word' => 'mahusay', 'negation' => 'hindi-mahusay', 'type' => 'positive', 'score' => 2.5],
            ['word' => 'hindi-mahusay', 'negation' => 'mahusay', 'type' => 'negative', 'score' => -2.5],
            
            ['word' => 'masaya', 'negation' => 'malungkot', 'type' => 'positive', 'score' => 2.5],
            ['word' => 'malungkot', 'negation' => 'masaya', 'type' => 'negative', 'score' => -2.5],
            
            ['word' => 'matalino', 'negation' => 'bobo', 'type' => 'positive', 'score' => 2.0],
            ['word' => 'bobo', 'negation' => 'matalino', 'type' => 'negative', 'score' => -2.0],
            
            ['word' => 'magaling', 'negation' => 'hindi-magaling', 'type' => 'positive', 'score' => 2.5],
            ['word' => 'hindi-magaling', 'negation' => 'magaling', 'type' => 'negative', 'score' => -2.5],
            
            ['word' => 'napakahusay', 'negation' => 'napakasama', 'type' => 'positive', 'score' => 3.0],
            ['word' => 'napakasama', 'negation' => 'napakahusay', 'type' => 'negative', 'score' => -3.0],
            
            ['word' => 'napakaganda', 'negation' => 'napakapangit', 'type' => 'positive', 'score' => 3.0],
            ['word' => 'napakapangit', 'negation' => 'napakaganda', 'type' => 'negative', 'score' => -3.0],
        ];

        foreach ($tagalogPairs as $pair) {
            // Check if word already exists
            $existingWord = SentimentWord::where('word', $pair['word'])
                                        ->where('language', 'tl')
                                        ->first();
            
            if ($existingWord) {
                // Update existing word with negation
                $existingWord->update([
                    'negation' => $pair['negation']
                ]);
            } else {
                // Create new word
                SentimentWord::create([
                    'word' => $pair['word'],
                    'negation' => $pair['negation'],
                    'type' => $pair['type'],
                    'score' => $pair['score'],
                    'language' => 'tl',
                    'is_active' => true
                ]);
            }
        }

        $this->command->info('Tagalog negation pairs added successfully!');
        $this->command->info('Total negation pairs: ' . (count($englishPairs) + count($tagalogPairs)));
    }
}
