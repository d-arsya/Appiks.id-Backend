<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $room = Room::first();
        $mentor = User::factory()->create([
            "verified" => true,
            "role" => "teacher",
            "mentor_id" => null,
            "room_id" => null,
            "school_id" => $room->school_id
        ]);
        User::factory()->create([
            "username" => "11111",
            "identifier" => "11111",
            "verified" => true,
            "role" => "admin",
            "mentor_id" => null,
            "room_id" => null,
            "school_id" => $room->school_id
        ]);
        User::factory()->create([
            "username" => "0000000000",
            "identifier" => "0000000000",
            "verified" => false,
            "role" => "student",
            "mentor_id" => $mentor->id,
            "room_id" => $room->id,
            "school_id" => $room->school_id
        ]);
    }
}
