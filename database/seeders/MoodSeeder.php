<?php

namespace Database\Seeders;

use App\Models\MoodRecord;
use App\Models\MoodStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MoodSeeder extends Seeder
{
    public function run()
    {
        $users = User::inRandomOrder()
            ->where('role', 'student')
            ->take(5)
            ->get();

        $weeksToGenerate = 2; // e.g., 2 weeks of data

        foreach ($users as $user) {
            // Start from the Monday of (today - N weeks)
            $startDate = now()
                ->startOfWeek(Carbon::MONDAY) // align to Monday
                ->subWeeks($weeksToGenerate);

            for ($w = 0; $w < $weeksToGenerate; $w++) {
                $weekStart = $startDate->copy()->addWeeks($w);
                $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SUNDAY);

                // Create mood status for this week
                $status = MoodStatus::factory()->create([
                    'start' => $weekStart->toDateString(),
                    'end' => $weekEnd->toDateString(),
                    'user_id' => $user->id,
                ]);

                // Create 7 mood records (one per day)
                for ($d = 0; $d < 7; $d++) {
                    MoodRecord::factory()->create([
                        'date' => $weekStart->copy()->addDays($d)->toDateString(),
                        'user_id' => $user->id,
                        'mood_status_id' => $status->id,
                    ]);
                }
            }
        }
    }
}
