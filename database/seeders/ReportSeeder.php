<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = User::whereRole('student')->get();
        $all = [];

        foreach ($students as $student) {
            // ambil 10 tanggal unik acak dalam 30 hari terakhir
            $dates = collect(range(0, 35))
                ->map(fn ($i) => Carbon::yesterday()->subDays($i))
                ->shuffle()
                ->take(10)
                ->values();

            foreach ($dates as $date) {
                $attrs = Report::factory()->raw([
                    'counselor_id' => $student->counselor_id,
                    'user_id' => $student->id,
                    'date' => $date->format('Y-m-d'),
                    'room' => 'Ruang BK 1',
                    'created_at' => $date->format('Y-m-d H:i:s'),
                    'updated_at' => $date->format('Y-m-d H:i:s'),
                ]);

                $all[] = $attrs;
            }
        }

        // sekali insert biar cepat
        Report::insert($all);
    }
}
