<?php

namespace Database\Seeders;

use App\Models\Gemini;
use Illuminate\Database\Seeder;

class GeminiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Gemini::factory(10)->create();
    }
}
