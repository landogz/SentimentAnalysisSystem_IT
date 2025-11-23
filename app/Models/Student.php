<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'student_number',
        'name',
        'email',
        'password',
        'year',
        'course',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the surveys for this student
     */
    public function surveys()
    {
        return $this->hasMany(Survey::class);
    }

    /**
     * Check if student has already submitted survey for teacher and subject
     */
    public function hasSubmittedSurvey($teacherId, $subjectId)
    {
        return $this->surveys()
            ->where('teacher_id', $teacherId)
            ->where('subject_id', $subjectId)
            ->exists();
    }
}
