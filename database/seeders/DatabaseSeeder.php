<?php

namespace Database\Seeders;

use App\Models\Course;
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
        // ── Admin ──────────────────────────────────────────────────────────────
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@edumex.com',
            'password' => Hash::make('admin1234'),
            'role'     => 'admin',
        ]);

        // ── Departments ────────────────────────────────────────────────────────
        $departments = [
            ['name' => 'Science',          'icon' => '🔬', 'description' => 'Biology, Chemistry, Physics'],
            ['name' => 'Mathematics',      'icon' => '📐', 'description' => 'Algebra, Calculus, Statistics'],
            ['name' => 'Technology',       'icon' => '💻', 'description' => 'Programming, Web, Data'],
            ['name' => 'Languages',        'icon' => '📖', 'description' => 'English, French, Arabic'],
            ['name' => 'Arts & Design',    'icon' => '🎨', 'description' => 'Visual design, Music, Film'],
            ['name' => 'History & Social', 'icon' => '🌍', 'description' => 'History, Geography, Civics'],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }

        // ── Test accounts ──────────────────────────────────────────────────────
        $teacher = User::create([
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

        // ── Sample courses ─────────────────────────────────────────────────────
        Course::create([
            'teacher_id'    => $teacher->id,
            'department_id' => 3, // Technology
            'icon'          => '💻',
            'title'         => 'Computer Science',
            'description'   => 'Learn programming, algorithms, and software development.',
        ]);

        Course::create([
            'teacher_id'    => $teacher->id,
            'department_id' => 2, // Mathematics
            'icon'          => '📐',
            'title'         => 'Advanced Mathematics',
            'description'   => 'Calculus, linear algebra, and statistics.',
        ]);

        $this->call(ChapterSeeder::class);
    }
}
