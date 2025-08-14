<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\School;
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
        $schools = School::all();
        $rooms = Room::all();
        $users = [];

        // super admin
        $users[] = User::factory()->raw([
            "role" => "super",
            "room_id" => null,
            "school_id" => null
        ]);

        // admin, headteacher, conselor
        foreach ($schools as $school) {
            $users[] = User::factory()->raw([
                "role" => "admin",
                "room_id" => null,
                "school_id" => $school->id
            ]);

            $users[] = User::factory()->raw([
                "role" => "headteacher",
                "room_id" => null,
                "school_id" => $school->id
            ]);

            $users = array_merge(
                $users,
                User::factory(2)->raw([
                    "role" => "conselor",
                    "room_id" => null,
                    "school_id" => $school->id
                ])
            );
        }

        // teacher + students
        foreach ($rooms as $room) {
            $users[] = User::factory()->raw([
                "role" => "teacher",
                "room_id" => $room->id,
                "school_id" => $room->school_id
            ]);

            $users = array_merge(
                $users,
                User::factory(2)->raw([
                    "role" => "student",
                    "room_id" => $room->id,
                    "school_id" => $room->school_id
                ])
            );
        }

        // Single bulk insert
        User::insert($users);
    }
}
