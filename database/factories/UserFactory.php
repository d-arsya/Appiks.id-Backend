<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $password = bcrypt('password');
        return [
            "name" => fake()->name(),
            "email" => fake()->email(),
            "phone" => fake()->phoneNumber(),
            "identifier" => fake()->uuid(),
            "password" => $password,
            "verified" => fake()->numberBetween(0, 1),
            "role" => fake()->randomElement(['super', 'admin', 'teacher', 'student', 'conselor', 'headteacher']),
            "room_id" => fake()->randomNumber(),
            "school_id" => fake()->randomNumber()
        ];
    }
}
