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
            // NO CASCADE on student_id to avoid multiple cascade paths
            $table->foreignId('student_id')->constrained('users')->onDelete('no action');
            $table->text('comment_text');
            $table->timestamps();
        });

        Schema::create('comment_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_comment_id')->constrained('chapter_comments')->onDelete('cascade');
            // NO CASCADE on student_id to avoid multiple cascade paths
            $table->foreignId('student_id')->constrained('users')->onDelete('no action');
            $table->text('reply_text');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comment_replies');
        Schema::dropIfExists('chapter_comments');
    }
};
