<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $students = User::where('role', 'student')->get();
        $courses  = Course::all();

        if ($courses->isEmpty() || $students->isEmpty()) {
            return;
        }

        $students->each(function (User $student) use ($courses) {
            $count    = min(rand(2, 4), $courses->count());
            $selected = $courses->random($count);

            foreach ($selected as $course) {
                Enrollment::firstOrCreate(
                    ['student_id' => $student->id, 'course_id' => $course->id],
                    ['enrolled_at' => now(), 'progress_percentage' => rand(0, 100)]
                );
            }
        });
    }
}
