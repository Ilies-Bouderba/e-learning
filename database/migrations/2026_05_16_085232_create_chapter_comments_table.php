<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chapter_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained('chapters')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('no action');
            $table->text('comment_text');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chapter_comments');
    }
};
