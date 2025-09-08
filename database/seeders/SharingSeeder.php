<?php

namespace Database\Seeders;

use App\Models\Sharing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SharingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = User::where('role', 'student')->get();
        $all = [];

        foreach ($students as $student) {
            // 5 tanggal unik acak dalam 30 hari terakhir
            $dates = collect(range(0, 30))
                ->map(fn($i) => now()->subDays($i))
                ->shuffle()
                ->take(5)
                ->values();

            foreach ($dates as $date) {
                // ambil atribut default dari factory (raw) lalu override user_id + tanggal
                $attrs = Sharing::factory()->raw();

                $attrs['user_id'] = $student->id;
                // pastikan format datetime yang diterima MySQL
                $attrs['created_at'] = Carbon::parse($date)->format('Y-m-d H:i:s');
                $attrs['updated_at'] = Carbon::parse($date)->format('Y-m-d H:i:s');

                $all[] = $attrs;
            }
        }

        // insert dalam chunk (aman jika datanya besar)
        DB::table('sharings')->insert($all);
    }
}
