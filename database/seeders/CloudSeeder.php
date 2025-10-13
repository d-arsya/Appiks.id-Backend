<?php

namespace Database\Seeders;

use App\Models\Cloud;
use App\Models\User;
use Illuminate\Database\Seeder;

class CloudSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = User::whereRole('student')->get();

        foreach ($students as $student) {
            Cloud::factory()->create(['user_id' => $student->id]);
        }
    }
}
