<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => fake()->slug,
            'tagline' => fake()->sentence,
            'title' => fake()->sentence,
            'description' => fake()->paragraph,
            'image_name' => fake()->image,
            'released_at' => Carbon::yesterday(),
            'learnings' => [fake()->word, fake()->word, fake()->word],
        ];
    }

    public function released(?Carbon $date = null): self
    {
        return $this->state(
            fn ($attributes) => ['released_at' => $date ?? Carbon::now()]
        );
    }
}
