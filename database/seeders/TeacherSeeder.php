<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Teacher;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachers = [
            [
                'name' => 'Dr. Sarah Johnson',
                'email' => 'sarah.johnson@university.edu',
                'department' => 'Computer Science',
                'phone' => '+1-555-0101',
                'bio' => 'Expert in software engineering and database systems with 15 years of teaching experience.',
                'is_active' => true
            ],
            [
                'name' => 'Prof. Michael Chen',
                'email' => 'michael.chen@university.edu',
                'department' => 'Mathematics',
                'phone' => '+1-555-0102',
                'bio' => 'Specializes in advanced calculus and linear algebra. Published author with 20+ research papers.',
                'is_active' => true
            ],
            [
                'name' => 'Dr. Emily Rodriguez',
                'email' => 'emily.rodriguez@university.edu',
                'department' => 'Physics',
                'phone' => '+1-555-0103',
                'bio' => 'Quantum physics researcher with expertise in theoretical physics and experimental design.',
                'is_active' => true
            ],
            [
                'name' => 'Prof. David Thompson',
                'email' => 'david.thompson@university.edu',
                'department' => 'English Literature',
                'phone' => '+1-555-0104',
                'bio' => 'Shakespearean scholar and creative writing instructor with a passion for modern literature.',
                'is_active' => true
            ],
            [
                'name' => 'Dr. Lisa Wang',
                'email' => 'lisa.wang@university.edu',
                'department' => 'Chemistry',
                'phone' => '+1-555-0105',
                'bio' => 'Organic chemistry specialist with focus on sustainable materials and green chemistry.',
                'is_active' => true
            ],
            [
                'name' => 'Prof. James Wilson',
                'email' => 'james.wilson@university.edu',
                'department' => 'History',
                'phone' => '+1-555-0106',
                'bio' => 'Medieval history expert with extensive research in European cultural development.',
                'is_active' => true
            ],
            [
                'name' => 'Dr. Maria Garcia',
                'email' => 'maria.garcia@university.edu',
                'department' => 'Psychology',
                'phone' => '+1-555-0107',
                'bio' => 'Clinical psychologist specializing in cognitive behavioral therapy and research methods.',
                'is_active' => true
            ],
            [
                'name' => 'Prof. Robert Kim',
                'email' => 'robert.kim@university.edu',
                'department' => 'Economics',
                'phone' => '+1-555-0108',
                'bio' => 'Macroeconomic theory expert with experience in policy analysis and economic modeling.',
                'is_active' => true
            ]
        ];

        foreach ($teachers as $teacher) {
            Teacher::create($teacher);
        }
    }
}
