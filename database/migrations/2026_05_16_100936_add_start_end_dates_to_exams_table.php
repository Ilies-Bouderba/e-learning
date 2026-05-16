<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            if (!Schema::hasColumn('exams', 'start_date')) {
                $table->dateTime('start_date')->nullable();
            }
            if (!Schema::hasColumn('exams', 'end_date')) {
                $table->dateTime('end_date')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date']);
        });
    }
};
