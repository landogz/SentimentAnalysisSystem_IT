<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'department',
        'phone',
        'bio',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the subjects taught by this teacher
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'teacher_subject')
                    ->withPivot('is_primary')
                    ->withTimestamps();
    }

    /**
     * Get the surveys for this teacher
     */
    public function surveys(): HasMany
    {
        return $this->hasMany(Survey::class);
    }

    /**
     * Get the average rating for this teacher
     */
    public function getAverageRatingAttribute(): float
    {
        return $this->surveys()->avg('rating') ?? 0.0;
    }

    /**
     * Get the total number of surveys for this teacher
     */
    public function getTotalSurveysAttribute(): int
    {
        return $this->surveys()->count();
    }

    /**
     * Get sentiment statistics for this teacher
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
     * Scope for active teachers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
