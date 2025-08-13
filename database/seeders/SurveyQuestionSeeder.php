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
        // Option Questions (1-5 scale)
        $optionQuestions = [
            [
                'question_text' => 'How would you rate the teacher\'s knowledge of the subject matter?',
                'question_type' => 'option',
                'order_number' => 1,
                'is_active' => true
            ],
            [
                'question_text' => 'How effective is the teacher in explaining complex concepts?',
                'question_type' => 'option',
                'order_number' => 2,
                'is_active' => true
            ],
            [
                'question_text' => 'How well does the teacher organize and structure the course content?',
                'question_type' => 'option',
                'order_number' => 3,
                'is_active' => true
            ],
            [
                'question_text' => 'How responsive is the teacher to student questions and concerns?',
                'question_type' => 'option',
                'order_number' => 4,
                'is_active' => true
            ],
            [
                'question_text' => 'How would you rate the teacher\'s enthusiasm and passion for teaching?',
                'question_type' => 'option',
                'order_number' => 5,
                'is_active' => true
            ],
            [
                'question_text' => 'How well does the teacher use technology and teaching aids?',
                'question_type' => 'option',
                'order_number' => 6,
                'is_active' => true
            ],
            [
                'question_text' => 'How fair and consistent is the teacher in grading and evaluation?',
                'question_type' => 'option',
                'order_number' => 7,
                'is_active' => true
            ],
            [
                'question_text' => 'How would you rate the teacher\'s availability for consultation?',
                'question_type' => 'option',
                'order_number' => 8,
                'is_active' => true
            ],
            [
                'question_text' => 'How well does the teacher encourage student participation and interaction?',
                'question_type' => 'option',
                'order_number' => 9,
                'is_active' => true
            ],
            [
                'question_text' => 'Overall, how satisfied are you with this teacher\'s performance?',
                'question_type' => 'option',
                'order_number' => 10,
                'is_active' => true
            ]
        ];

        // Comment Questions
        $commentQuestions = [
            [
                'question_text' => 'What are the teacher\'s strengths? Please provide specific examples.',
                'question_type' => 'comment',
                'order_number' => 1,
                'is_active' => true
            ],
            [
                'question_text' => 'What areas could the teacher improve? Please provide constructive feedback.',
                'question_type' => 'comment',
                'order_number' => 2,
                'is_active' => true
            ],
            [
                'question_text' => 'How has this teacher helped you learn and understand the subject better?',
                'question_type' => 'comment',
                'order_number' => 3,
                'is_active' => true
            ],
            [
                'question_text' => 'What teaching methods or techniques did you find most effective?',
                'question_type' => 'comment',
                'order_number' => 4,
                'is_active' => true
            ],
            [
                'question_text' => 'What challenges did you face in this class and how did the teacher address them?',
                'question_type' => 'comment',
                'order_number' => 5,
                'is_active' => true
            ],
            [
                'question_text' => 'How well does the teacher create an inclusive and supportive learning environment?',
                'question_type' => 'comment',
                'order_number' => 6,
                'is_active' => true
            ],
            [
                'question_text' => 'What suggestions do you have for improving the course or teaching methods?',
                'question_type' => 'comment',
                'order_number' => 7,
                'is_active' => true
            ],
            [
                'question_text' => 'How well does the teacher connect theoretical concepts with practical applications?',
                'question_type' => 'comment',
                'order_number' => 8,
                'is_active' => true
            ],
            [
                'question_text' => 'What impact has this teacher had on your academic and personal development?',
                'question_type' => 'comment',
                'order_number' => 9,
                'is_active' => true
            ],
            [
                'question_text' => 'Any additional comments or suggestions for the teacher or the institution?',
                'question_type' => 'comment',
                'order_number' => 10,
                'is_active' => true
            ]
        ];

        // Insert option questions
        foreach ($optionQuestions as $question) {
            SurveyQuestion::create($question);
        }

        // Insert comment questions
        foreach ($commentQuestions as $question) {
            SurveyQuestion::create($question);
        }
    }
}
