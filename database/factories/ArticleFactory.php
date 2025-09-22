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
            "school_id" => fake()->randomNumber(),
            "title" => fake()->sentence(),
            "slug" => fake()->slug(),
            "description" => fake()->paragraph(),
            "thumbnail" => fake()->imageUrl(),
            "content" => fake()->randomHtml(),
        ];
    }
}
