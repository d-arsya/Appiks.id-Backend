<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\School>
 */
class SchoolFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $variants = fake()->randomElement(["SD", "SMP", "SMA", "MI", "MTS", "MA"]);
        $name = $variants . " " . fake()->name();
        return [
            "name" => $name
        ];
    }
}
