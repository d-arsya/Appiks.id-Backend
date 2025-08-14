<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SchoolSeeder::class,
            RoomSeeder::class,
            UserSeeder::class,
            ArticleSeeder::class,
            VideoSeeder::class,
            MoodSeeder::class,
            QuestionnaireSeeder::class,
            QuestionnaireAnswerSeeder::class,
            ScheduleSeeder::class,
            AnomalySeeder::class,
            MeetSeeder::class
        ]);
    }
}
