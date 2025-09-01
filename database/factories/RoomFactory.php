<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = "Kelas " . fake()->numberBetween(1, 6) . " " . fake()->randomElement(["A", "B", "C", "D"]);
        return [
            "name" => $name,
            "school_id" => fake()->randomNumber(),
            "code" => fake()->lexify("??????????")
        ];
    }
}
