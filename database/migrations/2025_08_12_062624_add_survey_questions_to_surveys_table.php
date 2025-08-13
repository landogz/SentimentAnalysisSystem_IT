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
        Schema::table('surveys', function (Blueprint $table) {
            // Option questions (1-10) - stored as integers (1-5 scale)
            $table->integer('option_q1')->nullable();
            $table->integer('option_q2')->nullable();
            $table->integer('option_q3')->nullable();
            $table->integer('option_q4')->nullable();
            $table->integer('option_q5')->nullable();
            $table->integer('option_q6')->nullable();
            $table->integer('option_q7')->nullable();
            $table->integer('option_q8')->nullable();
            $table->integer('option_q9')->nullable();
            $table->integer('option_q10')->nullable();
            
            // Comment questions (1-10) - stored as text
            $table->text('comment_q1')->nullable();
            $table->text('comment_q2')->nullable();
            $table->text('comment_q3')->nullable();
            $table->text('comment_q4')->nullable();
            $table->text('comment_q5')->nullable();
            $table->text('comment_q6')->nullable();
            $table->text('comment_q7')->nullable();
            $table->text('comment_q8')->nullable();
            $table->text('comment_q9')->nullable();
            $table->text('comment_q10')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            // Drop option question columns
            $table->dropColumn([
                'option_q1', 'option_q2', 'option_q3', 'option_q4', 'option_q5',
                'option_q6', 'option_q7', 'option_q8', 'option_q9', 'option_q10'
            ]);
            
            // Drop comment question columns
            $table->dropColumn([
                'comment_q1', 'comment_q2', 'comment_q3', 'comment_q4', 'comment_q5',
                'comment_q6', 'comment_q7', 'comment_q8', 'comment_q9', 'comment_q10'
            ]);
        });
    }
};
