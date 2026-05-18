<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Course> */
class CourseFactory extends Factory
{
    protected $model = Course::class;

    private array $icons = ['📚', '🔬', '📐', '💻', '🌍', '🎨', '⚗️', '📖', '🧬', '🎵', '🏛️', '🧮'];

    private array $titles = [
        'Biology Molecular', 'Advanced Mathematics', 'Web Development',
        'World History', 'Organic Chemistry', 'Visual Design',
        'Physics 101', 'English Literature', 'Data Structures', 'French Language',
    ];

    public function definition(): array
    {
        return [
            'teacher_id'    => User::factory()->teacher(),
            'department_id' => Department::inRandomOrder()->first()?->id ?? 1,
            'icon'          => fake()->randomElement($this->icons),
            'title'         => fake()->randomElement($this->titles),
            'description'   => fake()->paragraph(),
            'password'      => null,
        ];
    }
}
