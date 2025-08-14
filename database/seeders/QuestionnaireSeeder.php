<?php

namespace Database\Seeders;

use App\Models\Questionnaire;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionnaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Questionnaire::factory(5)->create(["type" => "safe"]);
        Questionnaire::factory(5)->create(["type" => "unsafe"]);
    }
}
