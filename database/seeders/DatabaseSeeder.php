<?php

namespace Database\Seeders;

use App\Models\User;
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
            SchoolSeeder::class,
            RoomSeeder::class,
            UserSeeder::class,
            QuestionnaireSeeder::class,
            TagSeeder::class,
            VideoSeeder::class,
            MoodRecordSeeder::class,
            ReportSeeder::class,
            SharingSeeder::class,
            QuotesSeeder::class
        ]);
    }
}
