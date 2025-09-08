<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MoodRecord>
 */
class MoodRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "user_id" => fake()->randomNumber(),
            "status" => fake()->randomElement(['happy', 'sad', 'angry', 'neutral']),
            "recorded" => fake()->date()
        ];
    }
}
