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

        $paragraphs = [];
        for ($i = 1; $i <= 5; $i++) {
            $subtitle = [
                'detail' => 0,
                'format' => 1, // bold
                'mode' => 'normal',
                'style' => '',
                'text' => "Subjudul $i",
                'type' => 'text',
                'version' => 1,
            ];

            $body = [
                'detail' => 0,
                'format' => 0,
                'mode' => 'normal',
                'style' => '',
                'text' => $this->faker->paragraph(6),
                'type' => 'text',
                'version' => 1,
            ];

            $paragraphs[] = [
                'children' => [$subtitle, $body],
                'direction' => 'ltr',
                'format' => '',
                'indent' => 0,
                'type' => 'paragraph',
                'version' => 1,
            ];
        }

        $content = [
            'root' => [
                'children' => $paragraphs,
                'direction' => 'ltr',
                'format' => '',
                'indent' => 0,
                'type' => 'root',
                'version' => 1,
            ],
        ];

        return [
            'school_id' => fake()->randomNumber(),
            'title' => fake()->sentence(),
            'slug' => fake()->slug(),
            'description' => fake()->paragraph(),
            'thumbnail' => fake()->imageUrl(),
            'content' => json_encode($content),
        ];
    }
}
