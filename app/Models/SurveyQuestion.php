<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SurveyQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_text',
        'question_type',
        'part',
        'order_number',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the responses for this question
     */
    public function responses(): HasMany
    {
        return $this->hasMany(SurveyResponse::class);
    }

    /**
     * Scope for active questions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for option type questions
     */
    public function scopeOptionType($query)
    {
        return $query->where('question_type', 'option');
    }

    /**
     * Scope for comment type questions
     */
    public function scopeCommentType($query)
    {
        return $query->where('question_type', 'comment');
    }

    /**
     * Scope for specific part
     */
    public function scopePart($query, $part)
    {
        return $query->where('part', $part);
    }

    /**
     * Get the question type label
     */
    public function getQuestionTypeLabelAttribute(): string
    {
        return ucfirst($this->question_type);
    }

    /**
     * Get the part label
     */
    public function getPartLabelAttribute(): string
    {
        return match($this->part) {
            'part1' => 'Part 1 - Instructor Evaluation',
            'part2' => 'Part 2 - Difficulty Level',
            'part3' => 'Part 3 - Open Comments',
            default => 'Unknown Part'
        };
    }

    /**
     * Get the part badge class
     */
    public function getPartBadgeClassAttribute(): string
    {
        return match($this->part) {
            'part1' => 'badge-primary',
            'part2' => 'badge-warning',
            'part3' => 'badge-info',
            default => 'badge-secondary'
        };
    }

    /**
     * Get the status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return $this->is_active ? 'badge-success' : 'badge-danger';
    }

    /**
     * Get the status label
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }
}
