<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\Teacher;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachers = Teacher::all();
        
        $subjects = [
            [
                'subject_code' => 'CS101',
                'name' => 'Introduction to Computer Science',
                'description' => 'Fundamental concepts of programming and computer science principles.',
                'teacher_ids' => [1, 2] // Multiple teachers
            ],
            [
                'subject_code' => 'CS201',
                'name' => 'Data Structures and Algorithms',
                'description' => 'Advanced programming concepts and algorithm design.',
                'teacher_ids' => [1, 3]
            ],
            [
                'subject_code' => 'CS301',
                'name' => 'Database Systems',
                'description' => 'Database design, SQL, and data management.',
                'teacher_ids' => [2]
            ],
            [
                'subject_code' => 'CS401',
                'name' => 'Software Engineering',
                'description' => 'Software development methodologies and best practices.',
                'teacher_ids' => [3, 4]
            ],
            [
                'subject_code' => 'MATH101',
                'name' => 'Calculus I',
                'description' => 'Introduction to differential and integral calculus.',
                'teacher_ids' => [5]
            ],
            [
                'subject_code' => 'MATH201',
                'name' => 'Linear Algebra',
                'description' => 'Vector spaces, matrices, and linear transformations.',
                'teacher_ids' => [5, 6]
            ],
            [
                'subject_code' => 'PHYS101',
                'name' => 'Physics I',
                'description' => 'Classical mechanics and thermodynamics.',
                'teacher_ids' => [7]
            ],
            [
                'subject_code' => 'PHYS201',
                'name' => 'Physics II',
                'description' => 'Electromagnetism and modern physics.',
                'teacher_ids' => [7, 8]
            ],
            [
                'subject_code' => 'ENG101',
                'name' => 'English Composition',
                'description' => 'Academic writing and communication skills.',
                'teacher_ids' => [1]
            ],
            [
                'subject_code' => 'ENG201',
                'name' => 'Advanced Writing',
                'description' => 'Advanced writing techniques and analysis.',
                'teacher_ids' => [2]
            ],
            [
                'subject_code' => 'BIO101',
                'name' => 'Biology I',
                'description' => 'Introduction to biological concepts and principles.',
                'teacher_ids' => [3]
            ],
            [
                'subject_code' => 'BIO201',
                'name' => 'Biology II',
                'description' => 'Advanced biological concepts and laboratory techniques.',
                'teacher_ids' => [3, 4]
            ],
            [
                'subject_code' => 'CHEM101',
                'name' => 'Chemistry I',
                'description' => 'Fundamental principles of chemistry.',
                'teacher_ids' => [5]
            ],
            [
                'subject_code' => 'CHEM201',
                'name' => 'Chemistry II',
                'description' => 'Advanced chemistry concepts and laboratory work.',
                'teacher_ids' => [5, 6]
            ],
            [
                'subject_code' => 'HIST101',
                'name' => 'World History',
                'description' => 'Survey of world history from ancient to modern times.',
                'teacher_ids' => [7]
            ],
            [
                'subject_code' => 'HIST201',
                'name' => 'Modern History',
                'description' => 'History from the 18th century to present day.',
                'teacher_ids' => [7, 8]
            ]
        ];

        foreach ($subjects as $subjectData) {
            $teacherIds = $subjectData['teacher_ids'];
            unset($subjectData['teacher_ids']);
            
            $subject = Subject::create($subjectData);
            
            // Attach teachers to the subject
            $pivotData = [];
            foreach ($teacherIds as $index => $teacherId) {
                $pivotData[$teacherId] = [
                    'is_primary' => ($index === 0), // First teacher is primary
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            
            $subject->teachers()->attach($pivotData);
        }
    }
}
