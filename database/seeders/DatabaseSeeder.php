<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        // Seed teachers and subjects
        $this->call([
            TeacherSeeder::class,
            SubjectSeeder::class,
            SentimentWordSeeder::class,
            SurveySeeder::class,
            SurveyQuestionSeeder::class,
        ]);
    }
}
