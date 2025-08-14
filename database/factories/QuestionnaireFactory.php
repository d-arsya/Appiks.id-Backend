<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Questionnaire>
 */
class QuestionnaireFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'question' => fake()->sentence(),
            'answers' => [
                fake()->sentence(),
                fake()->sentence(),
                fake()->sentence(),
                fake()->sentence(),
            ],
            'type' => fake()->randomElement(['safe', 'unsafe']),
        ];
    }
}
