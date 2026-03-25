<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use App\Models\Cour;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@edumex.com',
            'password' => Hash::make('admin1234'),
            'role'     => 'admin',
        ]);

        $departments = [
            ['name' => 'Science',          'description' => 'Biology, Chemistry, Physics'],
            ['name' => 'Mathematics',      'description' => 'Algebra, Calculus, Statistics'],
            ['name' => 'Technology',       'description' => 'Programming, Web, Data'],
            ['name' => 'Languages',        'description' => 'English, French, Arabic'],
            ['name' => 'Arts & Design',    'description' => 'Visual design, Music, Film'],
            ['name' => 'History & Social', 'description' => 'History, Geography, Civics'],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }

        $teachers = User::factory()->teacher()->count(5)->create();

        User::factory()->student()->count(20)->create();

        $teachers->each(function ($teacher) {
            Cour::factory()->count(3)->create(['teacher_id' => $teacher->id]);
        });

        User::create([
            'name'     => 'Test Teacher',
            'email'    => 'teacher@test.com',
            'password' => Hash::make('password'),
            'role'     => 'teacher',
        ]);

        User::create([
            'name'     => 'Test Student',
            'email'    => 'student@test.com',
            'password' => Hash::make('password'),
            'role'     => 'student',
        ]);

        $this->call([
            ChapterSeeder::class,
            EnrollmentSeeder::class,
        ]);
    }
}
