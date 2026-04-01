<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\Cour;
use Illuminate\Database\Seeder;

class ChapterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = Cour::all();

        $courses->each(function ($course) {
            $chapterCount = rand(3, 5);
            Chapter::factory()
                ->count($chapterCount)
                ->create(['course_id' => $course->id]);
        });
    }
}
