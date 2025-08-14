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
            'type' => fake()->randomElement(['netral', 'happy', 'sad', 'angry']),
            'date' => fake()->date(),
            'user_id' => 1,
            'mood_status_id' => fake()->randomNumber(),
        ];
    }
}
