<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            if (!Schema::hasColumn('exams', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('exams', 'duration_minutes')) {
                $table->integer('duration_minutes')->nullable();
            }
            if (!Schema::hasColumn('exams', 'scheduled_date')) {
                $table->dateTime('scheduled_date')->nullable();
            }
            if (!Schema::hasColumn('exams', 'is_published')) {
                $table->boolean('is_published')->default(false);
            }
        });
    }

    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn(['description', 'duration_minutes', 'scheduled_date', 'is_published']);
        });
    }
};

