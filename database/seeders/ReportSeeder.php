<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            $dates = collect(range(0, 60))
                ->map(fn($i) => Carbon::today()->subDays($i))
                ->shuffle()
                ->take(25)
                ->values();

            foreach ($dates as $date) {
                $attrs = Report::factory()->raw([
                    "user_id" => $student->id,
                    "date"    => $date->format('Y-m-d'),
                ]);

                $all[] = $attrs;
            }
        }

        // sekali insert biar cepat
        Report::insert($all);
    }
}
