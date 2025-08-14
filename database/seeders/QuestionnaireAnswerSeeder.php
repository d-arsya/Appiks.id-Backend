<?php

namespace Database\Seeders;

use App\Models\QuestionnaireAnswer;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuestionnaireAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::inRandomOrder()->where('role', 'student')->take(4)->get();
        foreach ($users as $item) {
            QuestionnaireAnswer::factory(2)->create(["user_id" => $item->id]);
        }
    }
}
