<?php

namespace Database\Factories;

use App\Models\Location;
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
        $loc = Location::inRandomOrder()->first();
        $variants = fake()->randomElement(['SD', 'SMP', 'SMA', 'MI', 'MTS', 'MA']);
        $name = $variants.' '.fake()->name();

        return [
            'name' => $name,
            'phone' => fake()->phoneNumber(),
            'email' => fake()->safeEmail(),
            'district' => $loc->district,
            'city' => $loc->city,
            'province' => $loc->province,
            'address' => "{$loc->district}, {$loc->city}, {$loc->province}",
        ];
    }
}
