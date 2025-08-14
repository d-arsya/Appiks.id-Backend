<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $conselor = User::where('role', 'conselor')->get();
        foreach ($conselor as $item) {
            Schedule::factory(1)->create(["user_id" => $item->id]);
        }
    }
}
