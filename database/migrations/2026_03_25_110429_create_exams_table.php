<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Consolidated exam migration.
 *
 * Replaces the original 5 fragmented migrations:
 *   - 2026_03_25_110429_exam.php                         (initial exams table)
 *   - 2026_05_16_100304_add_missing_columns_to_exams_table.php
 *   - 2026_05_16_100918_create_exam_questions_table.php
 *   - 2026_05_16_100936_add_start_end_dates_to_exams_table.php
 *   - 2026_05_16_101534_drop_scheduled_date_from_exams_table.php
 *   - 2026_05_16_101639_create_exam_attempts_table.php
 *
 * The final schema has: exams, exam_questions, exam_attempts.
 * `scheduled_date` is intentionally omitted (it was added then immediately dropped).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('cours')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->integer('total_score')->default(0);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });

        Schema::create('exam_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->text('question_text');
            $table->text('model_answer')->nullable();
            $table->text('grading_criteria')->nullable();
            $table->integer('points')->default(1);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // NO CASCADE on exam_id – SQL Server disallows multiple cascade paths
        Schema::create('exam_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('exam_id')->constrained('exams')->onDelete('no action');
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->json('answers')->nullable();
            $table->json('ai_grades')->nullable();
            $table->integer('total_score')->default(0);
            $table->boolean('is_graded')->default(false);
            $table->unique(['student_id', 'exam_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_attempts');
        Schema::dropIfExists('exam_questions');
        Schema::dropIfExists('exams');
    }
};
