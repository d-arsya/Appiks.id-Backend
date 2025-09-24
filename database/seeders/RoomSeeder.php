<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\School;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $school = School::first();
        Room::factory()->create(['school_id' => $school->id, 'code' => 'aa11aa11']);
        Room::factory()->create(['school_id' => $school->id, 'code' => 'bb22bb22']);
    }
}
