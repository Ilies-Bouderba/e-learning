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
        // Create cours table first
        Schema::create('cours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('department_id')
                ->constrained('departments')
                ->onDelete('cascade');
            $table->string('icon')->default('📚');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
        });

        // Create announcements table
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('cours')->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->timestamp('posted_at')->useCurrent();
            $table->timestamps();
        });

        // Create enrollments table - NO CASCADE on course_id to avoid multiple cascade paths
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('cours')->onDelete('no action');
            $table->timestamp('enrolled_at')->useCurrent();
            $table->integer('progress_percentage')->default(0);
            $table->unique(['student_id', 'course_id']);
            $table->timestamps();
        });

        // Create comments table - NO CASCADE on course_id
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('cours')->onDelete('no action');
            $table->text('comment_text');
            $table->timestamp('posted_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('cours');
    }
};
