<?php
// database/factories/CourFactory.php
namespace Database\Factories;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourFactory extends Factory
{
    public function definition(): array
    {
        $icons = ['📚', '🔬', '📐', '💻', '🌍', '🎨', '⚗️', '📖', '🧬', '🎵', '🏛️', '🧮'];

        return [
            'teacher_id'    => User::factory()->teacher(),
            'department_id' => Department::inRandomOrder()->first()?->id ?? 1,
            'icon'          => fake()->randomElement($icons),
            'title'         => fake()->randomElement([
                'Biology Molecular', 'Advanced Mathematics', 'Web Development',
                'World History', 'Organic Chemistry', 'Visual Design',
                'Physics 101', 'English Literature', 'Data Structures', 'French Language',
            ]),
            'description'   => fake()->paragraph(),
            'password'      => null,
        ];
    }
}
