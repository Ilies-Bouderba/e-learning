<?php

namespace Database\Factories;

use App\Models\Cour;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<cour>
 */
class CourFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'icon'        => "⚗️",
            'teacher_id'  => User::factory()->teacher(),
            'title'       => fake()->randomElement([
                'Biology Molecular',
                'Advanced Mathematics',
                'Web Development',
                'World History',
                'Organic Chemistry',
                'Visual Design',
                'Physics 101',
                'English Literature',
                'Data Structures',
                'French Language',
            ]),
            'description' => fake()->paragraph(),
        ];
    }
}
