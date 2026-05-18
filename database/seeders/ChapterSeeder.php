<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\Course;
use Illuminate\Database\Seeder;

class ChapterSeeder extends Seeder
{
    public function run(): void
    {
        Course::all()->each(function (Course $course) {
            $count = rand(3, 5);
            Chapter::factory()->count($count)->create(['course_id' => $course->id]);
        });
    }
}
