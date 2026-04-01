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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('cours')->onDelete('cascade');
            $table->string('title');
            $table->integer('duration_minutes');
            $table->dateTime('scheduled_date');
            $table->integer('total_score')->default(100);
            $table->timestamps();
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->string('question_text');
            $table->string('type')->default('mcq'); // mcq, true_false, short_answer
            $table->integer('points')->default(1);
            $table->timestamps();
        });

        Schema::create('student_exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            // Remove cascade on exam_id to avoid multiple cascade paths
            $table->foreignId('exam_id')->constrained('exams')->onDelete('no action');
            $table->text('answers')->nullable();
            $table->integer('score')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_exams');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('exams');
    }
};
