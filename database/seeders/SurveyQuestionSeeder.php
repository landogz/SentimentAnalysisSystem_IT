<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SurveyQuestion;

class SurveyQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete existing questions
        SurveyQuestion::query()->delete();

        // Part 1: Instructor Evaluation (Outstanding, Very Satisfactory, Satisfactory, Fair, Poor)
        $part1Questions = [
            // Section A: Commitment
            [
                'question_text' => 'Demonstrates sensitivity to students\' ability to attend and absorb content information.',
                'question_type' => 'option',
                'part' => 'part1',
                'order_number' => 1,
                'is_active' => true
            ],
            [
                'question_text' => 'Integrates sensitively his/her learning objectives with those of the students in a collaborative process.',
                'question_type' => 'option',
                'part' => 'part1',
                'order_number' => 2,
                'is_active' => true
            ],
            [
                'question_text' => 'Makes self-available to students beyond official time.',
                'question_type' => 'option',
                'part' => 'part1',
                'order_number' => 3,
                'is_active' => true
            ],
            [
                'question_text' => 'Regularly comes to class on time, well-groomed and well-prepared to complete assigned responsibilities.',
                'question_type' => 'option',
                'part' => 'part1',
                'order_number' => 4,
                'is_active' => true
            ],
            [
                'question_text' => 'Keeps accurate records of students\' performance and prompt submission of the same.',
                'question_type' => 'option',
                'part' => 'part1',
                'order_number' => 5,
                'is_active' => true
            ],
            
            // Section B: Knowledge of Subject
            [
                'question_text' => 'Demonstrates mastery of the subject matter (explain the subject matter without relying solely on the prescribed textbook).',
                'question_type' => 'option',
                'part' => 'part1',
                'order_number' => 6,
                'is_active' => true
            ],
            [
                'question_text' => 'Draws and share information on the state on the art of theory and practice in his/her discipline.',
                'question_type' => 'option',
                'part' => 'part1',
                'order_number' => 7,
                'is_active' => true
            ],
            [
                'question_text' => 'Integrates subject to practical circumstances and learning intents/purposes of students.',
                'question_type' => 'option',
                'part' => 'part1',
                'order_number' => 8,
                'is_active' => true
            ],
            [
                'question_text' => 'Explains the relevance of present topics to the previous lessons, and relates the subject matter to relevant current issues and/or daily life activities.',
                'question_type' => 'option',
                'part' => 'part1',
                'order_number' => 9,
                'is_active' => true
            ],
            [
                'question_text' => 'Demonstrates up-to-date knowledge and/or awareness on current trends and issues of the subject.',
                'question_type' => 'option',
                'part' => 'part1',
                'order_number' => 10,
                'is_active' => true
            ],
            
            // Section C: Teaching for Independent Learning
            [
                'question_text' => 'Creates teaching strategies that allow students to practice using concepts they need to understand (interactive discussion).',
                'question_type' => 'option',
                'part' => 'part1',
                'order_number' => 11,
                'is_active' => true
            ],
            [
                'question_text' => 'Enhances student self-esteem and/or gives due recognition to students\' performance/potentials.',
                'question_type' => 'option',
                'part' => 'part1',
                'order_number' => 12,
                'is_active' => true
            ],
            [
                'question_text' => 'Allows students to create their own course with objectives and realistically defined student-professor rules and make them accountable for their performance.',
                'question_type' => 'option',
                'part' => 'part1',
                'order_number' => 13,
                'is_active' => true
            ],
            [
                'question_text' => 'Allows students to think independently and make their own decisions and holding them accountable for their performance based largely on their success in executing decisions.',
                'question_type' => 'option',
                'part' => 'part1',
                'order_number' => 14,
                'is_active' => true
            ],
            [
                'question_text' => 'Encourages students to learn beyond what is required and help/guide the students how to apply the concepts learned.',
                'question_type' => 'option',
                'part' => 'part1',
                'order_number' => 15,
                'is_active' => true
            ],
            
            // Section D: Management of Learning
            [
                'question_text' => 'Creates opportunities for intensive and/or extensive contribution of students in the class activities (e.g. breaks class into dyads, triads or buzz/task groups).',
                'question_type' => 'option',
                'part' => 'part1',
                'order_number' => 16,
                'is_active' => true
            ],
            [
                'question_text' => 'Assumes roles as facilitator, resource person, coach, inquisitor, integrator, referee in drawing students to contribute to knowledge and understanding of the concepts at hands.',
                'question_type' => 'option',
                'part' => 'part1',
                'order_number' => 17,
                'is_active' => true
            ],
            [
                'question_text' => 'Designs and implements learning conditions and experience that promotes healthy exchange and/or confrontations.',
                'question_type' => 'option',
                'part' => 'part1',
                'order_number' => 18,
                'is_active' => true
            ],
            [
                'question_text' => 'Structures/re-structures learning and teaching-learning context to enhance attainment of collective learning objectives.',
                'question_type' => 'option',
                'part' => 'part1',
                'order_number' => 19,
                'is_active' => true
            ],
            [
                'question_text' => 'Use of Instructional Materials (audio/video materials: fieldtrips, film showing, computer aided instruction and etc.) to reinforces learning processes.',
                'question_type' => 'option',
                'part' => 'part1',
                'order_number' => 20,
                'is_active' => true
            ]
        ];

        // Part 2: Difficulty Level (Very Difficult, Difficult, Slightly Difficult, Not Difficult, Very Not Difficult)
        $part2Questions = [
            [
                'question_text' => 'How would you rate the difficulty of understanding the concepts taught in the CCIT subject?',
                'question_type' => 'option',
                'part' => 'part2',
                'order_number' => 1,
                'is_active' => true
            ],
            [
                'question_text' => 'How would you rate the difficulty of completing CCIT assignments and projects?',
                'question_type' => 'option',
                'part' => 'part2',
                'order_number' => 2,
                'is_active' => true
            ],
            [
                'question_text' => 'How would you rate the difficulty of preparing for CCIT quizzes and exams?',
                'question_type' => 'option',
                'part' => 'part2',
                'order_number' => 3,
                'is_active' => true
            ],
            [
                'question_text' => 'How would you rate the difficulty of applying CCIT concepts in practical activities or exercises?',
                'question_type' => 'option',
                'part' => 'part2',
                'order_number' => 4,
                'is_active' => true
            ],
            [
                'question_text' => 'How would you rate the difficulty of keeping up with the pace of lessons in CCIT?',
                'question_type' => 'option',
                'part' => 'part2',
                'order_number' => 5,
                'is_active' => true
            ]
        ];

        // Part 3: Open Comments
        $part3Questions = [
            [
                'question_text' => 'Do you enjoy or have an interest in this subject? Why or why not?',
                'question_type' => 'comment',
                'part' => 'part3',
                'order_number' => 1,
                'is_active' => true
            ],
            [
                'question_text' => 'Do you find this subject challenging?',
                'question_type' => 'comment',
                'part' => 'part3',
                'order_number' => 2,
                'is_active' => true
            ],
            [
                'question_text' => 'Are learning resources for this subject easily available?',
                'question_type' => 'comment',
                'part' => 'part3',
                'order_number' => 3,
                'is_active' => true
            ],
            [
                'question_text' => 'Is this subject useful in real-life situations outside of school?',
                'question_type' => 'comment',
                'part' => 'part3',
                'order_number' => 4,
                'is_active' => true
            ]
        ];

        // Insert all questions
        foreach ($part1Questions as $question) {
            SurveyQuestion::create($question);
        }

        foreach ($part2Questions as $question) {
            SurveyQuestion::create($question);
        }

        foreach ($part3Questions as $question) {
            SurveyQuestion::create($question);
        }

        $this->command->info('Survey questions seeded successfully with 3 parts structure!');
        $this->command->info('Part 1: Instructor Evaluation (20 questions) - Sections A, B, C, D');
        $this->command->info('Part 2: Difficulty Level (5 questions)');
        $this->command->info('Part 3: Open Comments (4 questions)');
    }
}
