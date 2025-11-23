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
            $table->foreignId('student_id')->nullable()->after('subject_id')->constrained()->onDelete('cascade');
            $table->unique(['student_id', 'teacher_id', 'subject_id'], 'unique_student_teacher_subject');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->dropUnique('unique_student_teacher_subject');
            $table->dropForeign(['student_id']);
            $table->dropColumn('student_id');
        });
    }
};
