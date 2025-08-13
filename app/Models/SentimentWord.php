<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentimentWord extends Model
{
    use HasFactory;

    protected $fillable = [
        'word',
        'type',
        'score',
        'language',
        'is_active'
    ];

    protected $casts = [
        'score' => 'decimal:1',
        'is_active' => 'boolean',
    ];

    /**
     * Scope for positive words
     */
    public function scopePositive($query)
    {
        return $query->where('type', 'positive');
    }

    /**
     * Scope for negative words
     */
    public function scopeNegative($query)
    {
        return $query->where('type', 'negative');
    }

    /**
     * Scope for neutral words
     */
    public function scopeNeutral($query)
    {
        return $query->where('type', 'neutral');
    }

    /**
     * Scope for active words
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific language
     */
    public function scopeLanguage($query, $language)
    {
        return $query->where('language', $language);
    }

    /**
     * Get words by type and language
     */
    public static function getWordsByType($type, $language = 'en')
    {
        return static::where('type', $type)
                    ->where('language', $language)
                    ->where('is_active', true)
                    ->pluck('word', 'score')
                    ->toArray();
    }

    /**
     * Get all words with scores for a language
     */
    public static function getAllWordsWithScores($language = 'en')
    {
        return static::where('language', $language)
                    ->where('is_active', true)
                    ->get()
                    ->groupBy('type')
                    ->map(function ($words) {
                        return $words->pluck('score', 'word')->toArray();
                    })
                    ->toArray();
    }
} 