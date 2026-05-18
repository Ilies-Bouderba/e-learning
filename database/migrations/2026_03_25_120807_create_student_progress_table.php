<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            // NO CASCADE on chapter_id – avoids multiple cascade paths through course
            $table->foreignId('chapter_id')->constrained('chapters')->onDelete('no action');
            $table->boolean('completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->unique(['student_id', 'chapter_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_progress');
    }
};
