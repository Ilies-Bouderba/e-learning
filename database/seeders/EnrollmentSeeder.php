<?php

namespace Database\Seeders;

use App\Models\Cour;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = User::where('role', 'student')->get();
        $courses = Cour::all();

        $students->each(function ($student) use ($courses) {
            $numberOfCourses = rand(2, 4);
            $selectedCourses = $courses->random($numberOfCourses);

            foreach ($selectedCourses as $course) {
                Enrollment::create([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'enrolled_at' => now(),
                    'progress_percentage' => rand(0, 100),
                ]);
            }
        });
    }
}
