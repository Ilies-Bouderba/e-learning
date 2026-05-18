<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        if (Course::count() === 0) {
            User::where('role', 'teacher')->get()->each(function (User $teacher) {
                Course::factory()->count(3)->create(['teacher_id' => $teacher->id]);
            });
        }
    }
}
