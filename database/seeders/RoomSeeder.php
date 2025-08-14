<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\School;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = School::all();
        foreach ($schools as $item) {
            Room::factory(2)->create(["school_id" => $item->id]);
        }
    }
}
