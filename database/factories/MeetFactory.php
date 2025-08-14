<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meet>
 */
class MeetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "day" => fake()->date(),
            "ended" => fake()->boolean(),
            "anomaly_id" => fake()->randomNumber(),
            "teacher_id" => fake()->randomNumber(),
            "student_id" => fake()->randomNumber(),
        ];
    }
}
