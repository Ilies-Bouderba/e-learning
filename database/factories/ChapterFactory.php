<?php

namespace Database\Factories;

use App\Models\Chapter;
use App\Models\Cour;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Chapter>
 */
class ChapterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'course_id' => Cour::factory(),
            'title' => fake()->sentence(3),
            'chapter_number' => fake()->numberBetween(1, 20),
            'content' => fake()->paragraphs(3, true),
        ];
    }
}
