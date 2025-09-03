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
            "username" => "33333",
            "identifier" => "33333",
            "verified" => true,
            "role" => "teacher",
            "mentor_id" => null,
            "counselor_id" => null,
            "room_id" => null,
            "school_id" => $room->school_id
        ]);
        User::factory()->create([
            "username" => "55555",
            "identifier" => "55555",
            "verified" => true,
            "role" => "super",
            "counselor_id" => null,
            "mentor_id" => null,
            "room_id" => null,
            "school_id" => $room->school_id
        ]);
        User::factory()->create([
            "username" => "44444",
            "identifier" => "44444",
            "verified" => true,
            "role" => "headteacher",
            "mentor_id" => null,
            "room_id" => null,
            "counselor_id" => null,
            "school_id" => $room->school_id
        ]);
        $counselor = User::factory()->create([
            "username" => "22222",
            "identifier" => "22222",
            "verified" => true,
            "role" => "counselor",
            "mentor_id" => null,
            "room_id" => null,
            "counselor_id" => null,
            "school_id" => $room->school_id
        ]);
        User::factory()->create([
            "username" => "11111",
            "identifier" => "11111",
            "verified" => true,
            "role" => "admin",
            "mentor_id" => null,
            "room_id" => null,
            "counselor_id" => null,
            "school_id" => $room->school_id
        ]);
        User::factory()->create([
            "username" => "00000",
            "identifier" => "00000",
            "verified" => true,
            "role" => "student",
            "mentor_id" => $mentor->id,
            "room_id" => $room->id,
            "counselor_id" => $counselor->id,
            "school_id" => $room->school_id
        ]);
        User::factory()->create([
            "username" => "0000000000",
            "identifier" => "0000000000",
            "verified" => false,
            "role" => "student",
            "mentor_id" => $mentor->id,
            "room_id" => $room->id,
            "counselor_id" => $counselor->id,
            "school_id" => $room->school_id
        ]);
        User::factory()->create([
            "username" => "1111111111",
            "identifier" => "1111111111",
            "verified" => false,
            "role" => "admin",
            "mentor_id" => $mentor->id,
            "room_id" => $room->id,
            "counselor_id" => null,
            "school_id" => $room->school_id
        ]);
    }
}
