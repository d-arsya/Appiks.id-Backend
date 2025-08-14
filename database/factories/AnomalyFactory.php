<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Anomaly>
 */
class AnomalyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $handled = fake()->boolean();

        return [
            'description' => fake()->sentence(),
            'handled' => $handled,
            'method' => $handled ? fake()->randomElement(['meet', 'chat']) : null,
            'user_id' => fake()->randomNumber()
        ];
    }
}
