<?php

namespace Database\Seeders;

use App\Models\Sharing;
use App\Models\User;
use Carbon\Carbon;
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
            $dates = collect(range(0, 35))
                ->map(fn ($i) => Carbon::yesterday()->subDays($i))
                ->shuffle()
                ->take(3)
                ->values();

            foreach ($dates as $date) {
                // buat raw data dari factory
                $attrs = Sharing::factory()->raw();

                // tentukan secara random apakah "filled" atau "empty"
                if (rand(0, 1)) {
                    // filled
                    $attrs['user_id'] = $student->id;
                    $attrs['created_at'] = Carbon::parse($date)->format('Y-m-d H:i:s');
                    $attrs['updated_at'] = Carbon::parse($date)->format('Y-m-d H:i:s');
                } else {
                    // empty
                    $attrs['reply'] = null;
                    $attrs['replied_by'] = null;
                    $attrs['replied_at'] = null;
                    $attrs['user_id'] = $student->id;
                    $attrs['created_at'] = Carbon::parse($date)->format('Y-m-d H:i:s');
                    $attrs['updated_at'] = Carbon::parse($date)->format('Y-m-d H:i:s');
                }

                $all[] = $attrs;
            }
        }

        // insert dalam chunk (aman jika datanya besar)
        DB::table('sharings')->insert($all);
    }
}
