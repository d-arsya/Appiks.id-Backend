<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SelfHelp>
 */
class SelfHelpFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function daily(): static
    {
        return $this->state(function () {
            return [
                'type' => 'Daily Journaling',
                'content' => [
                    'category' => fake()->word(),
                    'emotions' => [fake()->word(), fake()->word(), fake()->word()],
                    'story' => fake()->paragraph(),
                    'mind' => fake()->sentence(10),
                ],
            ];
        });
    }

    public function gratitude(): static
    {
        return $this->state(function () {
            return [
                'type' => 'Gratitude Journal',
                'content' => [
                    'achievement' => fake()->sentences(3),
                    'apreciation' => fake()->sentence(),
                    'progress' => [fake()->sentence(5), fake()->sentence(5), fake()->sentence(5), fake()->sentence(5)],
                ],
            ];
        });
    }

    public function grounding(): static
    {
        return $this->state(function () {
            return [
                'type' => 'Grounding Technique',
                'content' => [
                    'five' => fake()->words(5),
                    'four' => fake()->words(4),
                    'three' => fake()->words(3),
                    'two' => fake()->words(2),
                    'one' => fake()->word(),
                ],
            ];
        });
    }

    public function sensory(): static
    {
        return $this->state(function () {
            return [
                'type' => 'Sensory Relaxation',
                'content' => [
                    'activity' => fake()->sentences(4),
                    'reflection' => fake()->sentence(),
                ],
            ];
        });
    }

    public function definition(): array
    {
        return [
            'user_id' => fake()->randomNumber(),
            'type' => fake()->randomElement([
                'Daily Journaling',
                'Gratitude Journal',
                'Grounding Technique',
                'Sensory Relaxation',
            ]),
            'content' => [],
        ];
    }
}
