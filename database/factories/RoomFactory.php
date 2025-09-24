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
        $name = fake()->randomElement(['IPA', 'IPS']).' '.fake()->numberBetween(1, 6);

        return [
            'name' => $name,
            'school_id' => fake()->randomNumber(),
            'level' => fake()->randomElement(['X', 'XI', 'XII']),
            'code' => fake()->lexify('????????'),
        ];
    }
}
