<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_code',
        'name',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the teachers who teach this subject
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'teacher_subject')
                    ->withPivot('is_primary')
                    ->withTimestamps();
    }

    /**
     * Get the primary teacher for this subject
     */
    public function primaryTeacher()
    {
        return $this->teachers()->wherePivot('is_primary', true)->first();
    }

    /**
     * Get the surveys for this subject
     */
    public function surveys(): HasMany
    {
        return $this->hasMany(Survey::class);
    }

    /**
     * Get the average rating for this subject
     */
    public function getAverageRatingAttribute(): float
    {
        return $this->surveys()->avg('rating') ?? 0.0;
    }

    /**
     * Get the total number of surveys for this subject
     */
    public function getTotalSurveysAttribute(): int
    {
        return $this->surveys()->count();
    }

    /**
     * Get sentiment statistics for this subject
     */
    public function getSentimentStats(): array
    {
        $surveys = $this->surveys()->get();
        
        $positiveCount = $surveys->where('sentiment', 'positive')->count();
        $negativeCount = $surveys->where('sentiment', 'negative')->count();
        $neutralCount = $surveys->where('sentiment', 'neutral')->count();
        $total = $surveys->count();

        return [
            'positive' => [
                'count' => $positiveCount,
                'percentage' => $total > 0 ? round(($positiveCount / $total) * 100, 2) : 0
            ],
            'negative' => [
                'count' => $negativeCount,
                'percentage' => $total > 0 ? round(($negativeCount / $total) * 100, 2) : 0
            ],
            'neutral' => [
                'count' => $neutralCount,
                'percentage' => $total > 0 ? round(($neutralCount / $total) * 100, 2) : 0
            ],
            'total' => $total
        ];
    }

    /**
     * Scope for active subjects
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
