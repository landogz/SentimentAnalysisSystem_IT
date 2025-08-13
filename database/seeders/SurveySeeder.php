<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Survey;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\SurveyResponse;
use App\Models\SurveyQuestion;

class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing surveys and responses
        SurveyResponse::query()->delete();
        Survey::query()->delete();
        $this->command->info('Cleared existing surveys and responses...');

        $teachers = Teacher::all();
        $subjects = Subject::all();
        $questions = SurveyQuestion::active()->get();

        if ($teachers->isEmpty() || $subjects->isEmpty()) {
            $this->command->info('No teachers or subjects found. Please run TeacherSeeder and SubjectSeeder first.');
            return;
        }

        if ($questions->isEmpty()) {
            $this->command->info('No survey questions found. Please run SurveyQuestionSeeder first.');
            return;
        }

        // Sample survey data with part-specific analysis
        $sampleSurveys = [
            [
                'rating' => 4.5,
                'sentiment' => 'positive',
                'part1_rating' => 4.6, // Instructor Evaluation
                'part2_rating' => 4.2, // Difficulty Level
                'part3_sentiment' => 'positive',
                'feedback_text' => 'Excellent teaching methods and very engaging lectures. The instructor is very knowledgeable and makes complex topics easy to understand.',
                'student_name' => 'John Doe',
                'student_email' => 'john.doe@student.edu'
            ],
            [
                'rating' => 3.8,
                'sentiment' => 'positive',
                'part1_rating' => 4.0,
                'part2_rating' => 3.5,
                'part3_sentiment' => 'positive',
                'feedback_text' => 'Good course structure and helpful instructor. The subject is moderately challenging but manageable.',
                'student_name' => 'Jane Smith',
                'student_email' => 'jane.smith@student.edu'
            ],
            [
                'rating' => 4.2,
                'sentiment' => 'positive',
                'part1_rating' => 4.4,
                'part2_rating' => 3.8,
                'part3_sentiment' => 'positive',
                'feedback_text' => 'Very knowledgeable and approachable teacher. The course content is well-organized.',
                'student_name' => 'Mike Johnson',
                'student_email' => 'mike.johnson@student.edu'
            ],
            [
                'rating' => 2.5,
                'sentiment' => 'negative',
                'part1_rating' => 2.8,
                'part2_rating' => 4.5,
                'part3_sentiment' => 'negative',
                'feedback_text' => 'Could improve communication and course materials. The subject is very difficult and the instructor is not very helpful.',
                'student_name' => 'Sarah Wilson',
                'student_email' => 'sarah.wilson@student.edu'
            ],
            [
                'rating' => 3.0,
                'sentiment' => 'neutral',
                'part1_rating' => 3.2,
                'part2_rating' => 3.0,
                'part3_sentiment' => 'neutral',
                'feedback_text' => 'Average course, could be better organized. The difficulty level is appropriate.',
                'student_name' => 'David Brown',
                'student_email' => 'david.brown@student.edu'
            ],
            [
                'rating' => 4.8,
                'sentiment' => 'positive',
                'part1_rating' => 4.9,
                'part2_rating' => 4.0,
                'part3_sentiment' => 'positive',
                'feedback_text' => 'Outstanding instructor with great expertise. The course is challenging but very rewarding.',
                'student_name' => 'Emily Davis',
                'student_email' => 'emily.davis@student.edu'
            ],
            [
                'rating' => 3.5,
                'sentiment' => 'neutral',
                'part1_rating' => 3.6,
                'part2_rating' => 3.4,
                'part3_sentiment' => 'neutral',
                'feedback_text' => 'Decent course, some improvements needed. The instructor is okay but could be more engaging.',
                'student_name' => 'Robert Miller',
                'student_email' => 'robert.miller@student.edu'
            ],
            [
                'rating' => 4.0,
                'sentiment' => 'positive',
                'part1_rating' => 4.2,
                'part2_rating' => 3.6,
                'part3_sentiment' => 'positive',
                'feedback_text' => 'Good learning experience overall. The instructor is professional and the course is well-structured.',
                'student_name' => 'Lisa Garcia',
                'student_email' => 'lisa.garcia@student.edu'
            ],
            [
                'rating' => 1.8,
                'sentiment' => 'negative',
                'part1_rating' => 2.0,
                'part2_rating' => 4.8,
                'part3_sentiment' => 'negative',
                'feedback_text' => 'Terrible experience. The instructor is unprofessional and the course is extremely difficult without proper guidance.',
                'student_name' => 'Alex Chen',
                'student_email' => 'alex.chen@student.edu'
            ],
            [
                'rating' => 4.9,
                'sentiment' => 'positive',
                'part1_rating' => 5.0,
                'part2_rating' => 3.2,
                'part3_sentiment' => 'positive',
                'feedback_text' => 'Amazing instructor! Very passionate about teaching and makes learning enjoyable. The course is challenging but the instructor makes it manageable.',
                'student_name' => 'Maria Rodriguez',
                'student_email' => 'maria.rodriguez@student.edu'
            ]
        ];

        $totalSurveys = 0;

        foreach ($teachers as $teacher) {
            // Get subjects for this teacher
            $teacherSubjects = $teacher->subjects;
            
            if ($teacherSubjects->isEmpty()) {
                // If no subjects assigned, assign a random subject
                $randomSubject = $subjects->random();
                $teacher->subjects()->attach($randomSubject->id, ['is_primary' => true]);
                $teacherSubjects = collect([$randomSubject]);
            }

            // Create 3-6 surveys for each teacher
            $numSurveys = rand(3, 6);
            
            for ($i = 0; $i < $numSurveys; $i++) {
                $surveyData = $sampleSurveys[array_rand($sampleSurveys)];
                $subject = $teacherSubjects->random();
                
                // Create survey
                $survey = Survey::create([
                    'teacher_id' => $teacher->id,
                    'subject_id' => $subject->id,
                    'rating' => $surveyData['rating'],
                    'sentiment' => $surveyData['sentiment'],
                    'feedback_text' => $surveyData['feedback_text'],
                    'student_name' => $surveyData['student_name'],
                    'student_email' => $surveyData['student_email'],
                    'ip_address' => '127.0.0.1',
                    'created_at' => now()->subDays(rand(1, 60))
                ]);

                // Create survey responses for each part
                $this->createSurveyResponses($survey, $questions, $surveyData);
                
                $totalSurveys++;
            }
        }

        $this->command->info("Sample surveys created successfully!");
        $this->command->info("Total surveys created: $totalSurveys");
        $this->command->info("Total responses created: " . SurveyResponse::count());
    }

    /**
     * Create survey responses for all parts
     */
    private function createSurveyResponses($survey, $questions, $surveyData)
    {
        // Group questions by part
        $part1Questions = $questions->where('part', 'part1'); // Instructor Evaluation
        $part2Questions = $questions->where('part', 'part2'); // Difficulty Level
        $part3Questions = $questions->where('part', 'part3'); // Open Comments

        // Part 1: Instructor Evaluation (1-5 scale)
        foreach ($part1Questions as $question) {
            $rating = $this->generatePart1Rating($surveyData['part1_rating']);
            SurveyResponse::create([
                'survey_id' => $survey->id,
                'survey_question_id' => $question->id,
                'answer' => $rating
            ]);
        }

        // Part 2: Difficulty Level (1-5 scale)
        foreach ($part2Questions as $question) {
            $rating = $this->generatePart2Rating($surveyData['part2_rating']);
            SurveyResponse::create([
                'survey_id' => $survey->id,
                'survey_question_id' => $question->id,
                'answer' => $rating
            ]);
        }

        // Part 3: Open Comments
        foreach ($part3Questions as $question) {
            $comment = $this->generatePart3Comment($question, $surveyData['part3_sentiment']);
            SurveyResponse::create([
                'survey_id' => $survey->id,
                'survey_question_id' => $question->id,
                'answer' => $comment
            ]);
        }
    }

    /**
     * Generate Part 1 rating based on target rating
     */
    private function generatePart1Rating($targetRating)
    {
        // Generate rating with some variation around the target
        $variation = rand(-2, 2) / 10; // ±0.2 variation
        $rating = max(1, min(5, $targetRating + $variation));
        return round($rating);
    }

    /**
     * Generate Part 2 rating based on target rating
     */
    private function generatePart2Rating($targetRating)
    {
        // Generate rating with some variation around the target
        $variation = rand(-2, 2) / 10; // ±0.2 variation
        $rating = max(1, min(5, $targetRating + $variation));
        return round($rating);
    }

    /**
     * Generate Part 3 comment based on sentiment
     */
    private function generatePart3Comment($question, $sentiment)
    {
        $comments = [
            'positive' => [
                'Do you enjoy or have an interest in this subject? Why or why not?' => [
                    'Yes, I really enjoy this subject because the instructor makes it interesting and engaging.',
                    'Absolutely! The way the instructor teaches makes the subject very appealing.',
                    'Yes, I find this subject fascinating, especially with the instructor\'s teaching style.'
                ],
                'Do you find this subject challenging?' => [
                    'It\'s challenging but in a good way. The instructor provides excellent support.',
                    'Moderately challenging, but the instructor makes it manageable and enjoyable.',
                    'Yes, but the instructor\'s clear explanations help me understand the concepts.'
                ],
                'Are learning resources for this subject easily available?' => [
                    'Yes, the instructor provides excellent resources and materials.',
                    'Very much so. The instructor shares helpful resources regularly.',
                    'Yes, there are plenty of good resources available for this subject.'
                ],
                'Is this subject useful in real-life situations outside of school?' => [
                    'Definitely! The instructor shows practical applications which makes it very relevant.',
                    'Yes, I can see how this knowledge applies to real-world situations.',
                    'Absolutely, the instructor emphasizes practical applications.'
                ]
            ],
            'negative' => [
                'Do you enjoy or have an interest in this subject? Why or why not?' => [
                    'Not really, the instructor doesn\'t make it interesting.',
                    'No, the way it\'s taught makes it boring and unappealing.',
                    'I find it difficult to enjoy due to poor teaching methods.'
                ],
                'Do you find this subject challenging?' => [
                    'Yes, it\'s very challenging and the instructor doesn\'t provide enough help.',
                    'Extremely challenging, and the instructor doesn\'t explain things clearly.',
                    'Too challenging, and there\'s not enough support from the instructor.'
                ],
                'Are learning resources for this subject easily available?' => [
                    'No, the instructor doesn\'t provide adequate resources.',
                    'Not really, it\'s hard to find good materials for this subject.',
                    'Limited resources are available, which makes learning difficult.'
                ],
                'Is this subject useful in real-life situations outside of school?' => [
                    'Not sure, the instructor doesn\'t show practical applications.',
                    'The instructor doesn\'t explain how this applies to real life.',
                    'It\'s hard to see the practical value with the current teaching approach.'
                ]
            ],
            'neutral' => [
                'Do you enjoy or have an interest in this subject? Why or why not?' => [
                    'It\'s okay, neither particularly enjoyable nor boring.',
                    'I have mixed feelings about this subject.',
                    'It\'s acceptable, but could be more engaging.'
                ],
                'Do you find this subject challenging?' => [
                    'It\'s moderately challenging, about what I expected.',
                    'The difficulty level is reasonable.',
                    'It\'s challenging but manageable.'
                ],
                'Are learning resources for this subject easily available?' => [
                    'There are some resources available.',
                    'Resources are adequate but could be better.',
                    'The resources are okay, nothing special.'
                ],
                'Is this subject useful in real-life situations outside of school?' => [
                    'It might be useful, but the instructor doesn\'t emphasize this much.',
                    'Some parts seem relevant to real life.',
                    'I\'m not sure about the practical applications.'
                ]
            ]
        ];

        $questionText = $question->question_text;
        $sentimentComments = $comments[$sentiment][$questionText] ?? ['No comment available.'];
        
        return $sentimentComments[array_rand($sentimentComments)];
    }
} 