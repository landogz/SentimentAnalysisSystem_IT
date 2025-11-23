<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'subject_id',
        'student_id',
        'rating',
        'sentiment',
        'feedback_text',
        'survey_responses',
        'student_name',
        'student_email',
        'year',
        'course',
        'ip_address'
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'survey_responses' => 'array',
    ];

    /**
     * Get the teacher for this survey
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the subject for this survey
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the student for this survey
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the responses for this survey
     */
    public function responses(): HasMany
    {
        return $this->hasMany(SurveyResponse::class);
    }

    /**
     * Scope for positive sentiment surveys
     */
    public function scopePositive($query)
    {
        return $query->where('sentiment', 'positive');
    }

    /**
     * Scope for negative sentiment surveys
     */
    public function scopeNegative($query)
    {
        return $query->where('sentiment', 'negative');
    }

    /**
     * Scope for neutral sentiment surveys
     */
    public function scopeNeutral($query)
    {
        return $query->where('sentiment', 'neutral');
    }

    /**
     * Get the sentiment label
     */
    public function getSentimentLabelAttribute(): string
    {
        return ucfirst($this->sentiment);
    }

    /**
     * Get the sentiment badge class
     */
    public function getSentimentBadgeClassAttribute(): string
    {
        return match($this->sentiment) {
            'positive' => 'badge-success',
            'negative' => 'badge-danger',
            'neutral' => 'badge-warning',
            default => 'badge-secondary'
        };
    }
}
