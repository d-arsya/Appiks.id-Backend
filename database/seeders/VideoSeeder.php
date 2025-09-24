<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\Video;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $school = School::first();

        $videos = Video::factory(10)->create([
            'school_id' => $school->id,
        ]);

        foreach ($videos as $video) {
            // attach random tags from 1–4 (between 1 and 3 tags each)
            $video->tags()->attach(
                collect([1, 2, 3, 4])->random(rand(1, 3))
            );
        }
    }
}
