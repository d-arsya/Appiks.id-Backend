<?php

namespace Database\Seeders;

use App\Models\Anomaly;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnomalySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::inRandomOrder()->where('role', 'student')->take(10)->get();
        foreach ($users as $item) {
            Anomaly::factory(2)->create(["user_id" => $item->id]);
        }
    }
}
