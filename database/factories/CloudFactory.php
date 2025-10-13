<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cloud>
 */
class CloudFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $streak = fake()->numberBetween(1, 7);

        return [
            'user_id' => fake()->randomNumber(),
            'water' => fake()->numberBetween(10, 200),
            'level' => fake()->numberBetween(1, 1),
            'exp' => fake()->numberBetween(1, 90),
            'happiness' => fake()->numberBetween(1, 90),
            'streak' => $streak,
            'last_in' => fake()->randomElement([now()->subDay(), now()]),
        ];
    }
}
