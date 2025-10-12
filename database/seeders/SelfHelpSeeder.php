<?php

namespace Database\Seeders;

use App\Models\SelfHelp;
use App\Models\User;
use Illuminate\Database\Seeder;

class SelfHelpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = User::whereRole('student')->get();

        $types = ['gratitude', 'sensory', 'daily', 'grounding'];

        foreach ($students as $student) {
            for ($i = 0; $i < 30; $i++) {
                $date = now()->subDays($i)->startOfDay();
                $type = fake()->randomElement($types);

                SelfHelp::factory()
                    ->{$type}()
                    ->create([
                        'user_id' => $student->id,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);
            }
        }
    }
}
