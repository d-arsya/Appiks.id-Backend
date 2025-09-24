<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $youtubeIds = [
        'dQw4w9WgXcQ',
        '9bZkp7q19f0',
        '3JZ_D3ELwOQ',
        'kXYiU_JCYtU',
        'fLexgOxsZu0',
        '2Vv-BfVoq4g',
        'eVTXPUF4Oz4',
        '60ItHLz5WEA',
        'tAGnKpE4NCI',
        'ktvTqknDobU',
    ];

    public function definition(): array
    {
        return [
            'school_id' => fake()->randomNumber(),
            'title' => fake()->sentence(4),
            'description' => fake()->sentence(10),
            'thumbnail' => fake()->imageUrl(),
            'duration' => fake()->numberBetween(1, 59).':'.fake()->randomNumber(1, 59),
            'channel' => fake()->name(),
            'views' => fake()->numberBetween(100, 999),
            'video_id' => $this->youtubeIds[array_rand($this->youtubeIds)],
        ];
    }
}
