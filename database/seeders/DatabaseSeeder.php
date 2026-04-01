<?php

namespace Database\Seeders;

use App\Models\Cour;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@edumex.com',
            'password' => Hash::make('admin1234'),
            'role' => 'admin',
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

        $teacher = User::create([
            'name' => 'Test Teacher',
            'email' => 'teacher@test.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
        ]);

        User::create([
            'name' => 'Test Student',
            'email' => 'student@test.com',
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);

        Cour::create([
            'teacher_id' => $teacher->id,
            'department_id' => 1,
            'icon' => '💻',
            'title' => 'Computer Science',
            'description' => 'Learn programming, algorithms, and software development.',
            'password' => null,
        ]);

        Cour::create([
            'teacher_id' => $teacher->id,
            'department_id' => 2,
            'icon' => '📐',
            'title' => 'Advanced Mathematics',
            'description' => 'Calculus, linear algebra, and statistics.',
            'password' => null,
        ]);

        $this->call(ChapterSeeder::class);
    }
}
