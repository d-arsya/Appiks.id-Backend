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
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'name' => fake()->name(),
            'phone' => fake()->unique()->phoneNumber(),
            'username' => fake()->unique()->userName(),
            'identifier' => fake()->unique()->numberBetween(100000, 999999),
            'password' => Hash::make(config('app.default_password')),
            'verified' => fake()->boolean(),
            'role' => fake()->randomElement(['super', 'admin', 'teacher', 'student', 'conselor', 'headteacher']),
            'mentor_id' => fake()->randomNumber(),
            'counselor_id' => fake()->randomNumber(),
            'room_id' => fake()->randomNumber(),
            'school_id' => fake()->randomNumber(),
        ];
    }
}
