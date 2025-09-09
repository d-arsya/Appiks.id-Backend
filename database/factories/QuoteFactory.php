<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quotes>
 */
class QuoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "school_id" => fake()->randomNumber(),
            "text" => fake()->sentence(10),
            "author" => fake()->name(),
            "type" => fake()->randomElement(['secure', 'insecure', 'daily'])
        ];
    }
}
