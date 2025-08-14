<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuestionnaireAnswer>
 */
class QuestionnaireAnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['safe', 'unsafe', 'help']);

        if ($type === 'help') {
            return [
                'title' => null,
                'question' => null,
                'answers' => [$this->faker->sentence()],
                'type' => $type,
                'user_id' => fake()->randomNumber(),
            ];
        }

        // Generate 4 answers with one true in a random position
        $answers = [];
        $correctIndex = $this->faker->numberBetween(0, 3);

        for ($i = 0; $i < 4; $i++) {
            $answers[] = [
                $i === $correctIndex, // true only at the correct index
                $this->faker->sentence()
            ];
        }

        return [
            'title' => $this->faker->sentence(),
            'question' => $this->faker->sentence(),
            'answers' => $answers,
            'type' => $type,
            'user_id' => fake()->randomNumber(),
        ];
    }
}
