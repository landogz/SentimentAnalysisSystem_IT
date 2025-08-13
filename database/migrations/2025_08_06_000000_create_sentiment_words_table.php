<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sentiment_words', function (Blueprint $table) {
            $table->id();
            $table->string('word');
            $table->enum('type', ['positive', 'negative', 'neutral']);
            $table->decimal('score', 3, 1)->default(1.0); // Score from -5.0 to 5.0
            $table->string('language', 10)->default('en'); // Language code (en, tl, etc.)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['word', 'language']);
            $table->index(['type', 'language']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sentiment_words');
    }
}; 