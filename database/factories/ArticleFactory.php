<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "title" => fake()->sentence(),
            "content" => fake()->randomHtml(2, 6),
            "tags" => [fake()->word(), fake()->word(), fake()->word()],
            "type" => fake()->randomElement(['anger_management', 'self_help', 'inspiration']),
            "school_id" => fake()->randomNumber()
        ];
    }
}
