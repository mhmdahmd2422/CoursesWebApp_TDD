<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'slug' => fake()->slug,
            'vimeo_id' => mt_rand(10000000,99999999),
            'title' => fake()->word,
            'description' => fake()->sentence,
            'duration_in_mins' => fake()->numberBetween(1, 99),
        ];
    }
}
