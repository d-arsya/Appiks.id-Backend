<?php

namespace Database\Seeders;

use App\Models\Sharing;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SharingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = User::where('role', 'student')->get();

        foreach ($students as $item) {
            Sharing::factory(5)->create(["user_id" => $item->id]);
        }
    }
}
