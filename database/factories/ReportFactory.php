<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
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
            "topic" => fake()->sentence(4),
            "date" => fake()->date(),
            "time" => fake()->time('H:i'),
            "status" => fake()->randomElement(['menunggu', 'selesai', 'dibatalkan', 'disetujui']),
            "priority" => fake()->randomElement(['tinggi', 'rendah']),
            "notes" => fake()->sentence(10),
            "result" => fake()->sentence(10),
        ];
    }
}
