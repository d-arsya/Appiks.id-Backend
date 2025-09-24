<?php

namespace Database\Seeders;

use App\Models\MoodRecord;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MoodRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = User::where('role', 'student')->get();

        // ambil rentang 2 bulan terakhir
        $dates = collect(
            Carbon::today()->subDays(35)->startOfDay()->daysUntil(Carbon::yesterday())
        );

        $all = [];

        foreach ($students as $student) {
            foreach ($dates as $date) {
                $d = $date->copy()->setTime(rand(7, 20), rand(0, 59), rand(0, 59));
                $attrs = MoodRecord::factory()->raw([
                    'user_id' => $student->id,
                    'recorded' => $date->format('Y-m-d'),
                    'created_at' => $d,
                    'updated_at' => $d,
                ]);

                $all[] = $attrs;
            }
        }

        // insert sekaligus biar cepat
        foreach (array_chunk($all, 500) as $chunk) {
            MoodRecord::insert($chunk);
        }
    }
}
