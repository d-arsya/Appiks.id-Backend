<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\Video;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = School::all();
        foreach ($schools as $item) {
            Video::factory(2)->create(["school_id" => $item->id]);
        }
    }
}
