<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sharing>
 */
class SharingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => fake()->randomNumber(),
            'title' => fake()->sentence(),
            'description' => fake()->text(),
            'reply' => fake()->text(),
            'replied_at' => fake()->date(),
            'replied_by' => fake()->name(),
            'priority' => fake()->randomElement(['tinggi', 'rendah']),
            'created_at' => now(),
        ];
    }
}
