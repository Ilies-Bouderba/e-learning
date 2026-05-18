<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates: cours, announcements, enrollments.
 * NOTE: The original migration also created a `comments` table that was never
 * used in the application (chapter_comments replaced it). That table is omitted.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->string('icon', 10)->default('📚');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
        });

        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('cours')->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->timestamp('posted_at')->useCurrent();
            $table->timestamps();
        });

        // NO CASCADE on course_id to avoid multiple SQL Server cascade paths
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('cours')->onDelete('no action');
            $table->timestamp('enrolled_at')->useCurrent();
            $table->integer('progress_percentage')->default(0);
            $table->unique(['student_id', 'course_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('cours');
    }
};
