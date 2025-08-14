<?php

namespace Database\Seeders;

use App\Models\Anomaly;
use App\Models\Meet;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MeetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $anomalies = Anomaly::inRandomOrder()->take(4)->get();
        $teachers = User::where('role', 'conselor')->get('id');
        foreach ($anomalies as $item) {
            Meet::factory()->create(["anomaly_id" => $item->id, "student_id" => $item->user_id, "teacher_id" => fake()->randomElement($teachers)]);
        }
    }
}
