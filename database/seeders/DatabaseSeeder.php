<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // AiGenerated::class,
            // GeminiApi::class,
            LocationSeeder::class,
            SchoolSeeder::class,
            RoomSeeder::class,
            UserSeeder::class,
            QuestionnaireSeeder::class,
            TagSeeder::class,
            VideoSeeder::class,
            ArticleSeeder::class,
            MoodRecordSeeder::class,
            ReportSeeder::class,
            SharingSeeder::class,
            QuotesSeeder::class,
        ]);
    }
}
