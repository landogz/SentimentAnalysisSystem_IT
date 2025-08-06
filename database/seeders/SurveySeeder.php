<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Survey;
use App\Models\Teacher;
use App\Models\Subject;

class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachers = Teacher::all();
        $subjects = Subject::all();

        if ($teachers->isEmpty() || $subjects->isEmpty()) {
            $this->command->info('No teachers or subjects found. Please run TeacherSeeder and SubjectSeeder first.');
            return;
        }

        // Sample survey data
        $sampleSurveys = [
            [
                'rating' => 4.5,
                'sentiment' => 'positive',
                'feedback_text' => 'Excellent teaching methods and very engaging lectures.',
                'student_name' => 'John Doe',
                'student_email' => 'john.doe@student.edu'
            ],
            [
                'rating' => 3.8,
                'sentiment' => 'positive',
                'feedback_text' => 'Good course structure and helpful instructor.',
                'student_name' => 'Jane Smith',
                'student_email' => 'jane.smith@student.edu'
            ],
            [
                'rating' => 4.2,
                'sentiment' => 'positive',
                'feedback_text' => 'Very knowledgeable and approachable teacher.',
                'student_name' => 'Mike Johnson',
                'student_email' => 'mike.johnson@student.edu'
            ],
            [
                'rating' => 2.5,
                'sentiment' => 'negative',
                'feedback_text' => 'Could improve communication and course materials.',
                'student_name' => 'Sarah Wilson',
                'student_email' => 'sarah.wilson@student.edu'
            ],
            [
                'rating' => 3.0,
                'sentiment' => 'neutral',
                'feedback_text' => 'Average course, could be better organized.',
                'student_name' => 'David Brown',
                'student_email' => 'david.brown@student.edu'
            ],
            [
                'rating' => 4.8,
                'sentiment' => 'positive',
                'feedback_text' => 'Outstanding instructor with great expertise.',
                'student_name' => 'Emily Davis',
                'student_email' => 'emily.davis@student.edu'
            ],
            [
                'rating' => 3.5,
                'sentiment' => 'neutral',
                'feedback_text' => 'Decent course, some improvements needed.',
                'student_name' => 'Robert Miller',
                'student_email' => 'robert.miller@student.edu'
            ],
            [
                'rating' => 4.0,
                'sentiment' => 'positive',
                'feedback_text' => 'Good learning experience overall.',
                'student_name' => 'Lisa Garcia',
                'student_email' => 'lisa.garcia@student.edu'
            ]
        ];

        foreach ($teachers as $teacher) {
            // Get subjects for this teacher
            $teacherSubjects = $teacher->subjects;
            
            if ($teacherSubjects->isEmpty()) {
                // If no subjects assigned, assign a random subject
                $randomSubject = $subjects->random();
                $teacher->subjects()->attach($randomSubject->id, ['is_primary' => true]);
                $teacherSubjects = collect([$randomSubject]);
            }

            // Create 2-4 surveys for each teacher
            $numSurveys = rand(2, 4);
            
            for ($i = 0; $i < $numSurveys; $i++) {
                $surveyData = $sampleSurveys[array_rand($sampleSurveys)];
                $subject = $teacherSubjects->random();
                
                Survey::create([
                    'teacher_id' => $teacher->id,
                    'subject_id' => $subject->id,
                    'rating' => $surveyData['rating'],
                    'sentiment' => $surveyData['sentiment'],
                    'feedback_text' => $surveyData['feedback_text'],
                    'student_name' => $surveyData['student_name'],
                    'student_email' => $surveyData['student_email'],
                    'ip_address' => '127.0.0.1',
                    'created_at' => now()->subDays(rand(1, 30))
                ]);
            }
        }

        $this->command->info('Sample surveys created successfully!');
    }
} 