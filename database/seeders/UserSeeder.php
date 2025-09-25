<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = Room::all();
        $sch = $rooms[0]->school_id;
        $mentor = User::factory()->create([
            'username' => 'guruwali',
            'verified' => true,
            'role' => 'teacher',
            'mentor_id' => null,
            'counselor_id' => null,
            'room_id' => null,
            'school_id' => $sch,
        ]);
        $counselor = User::factory()->create([
            'username' => 'gurubk',
            'verified' => true,
            'role' => 'counselor',
            'mentor_id' => null,
            'room_id' => null,
            'counselor_id' => null,
            'school_id' => $sch,
        ]);
        $con = $counselor->id;
        $men = $mentor->id;
        User::factory()->create([
            'username' => 'super',
            'verified' => true,
            'role' => 'super',
            'counselor_id' => null,
            'mentor_id' => null,
            'room_id' => null,
            'school_id' => null,
        ]);
        User::factory()->create([
            'username' => 'kepsek',
            'verified' => true,
            'role' => 'headteacher',
            'mentor_id' => null,
            'room_id' => null,
            'counselor_id' => null,
            'school_id' => $sch,
        ]);
        User::factory()->create([
            'username' => 'admintu',
            'verified' => true,
            'role' => 'admin',
            'mentor_id' => null,
            'room_id' => null,
            'counselor_id' => null,
            'school_id' => $sch,
        ]);
        foreach ($rooms as $room) {
            User::factory()->create([
                'username' => "siswa{$room->id}active",
                'verified' => true,
                'role' => 'student',
                'mentor_id' => $men,
                'room_id' => $room->id,
                'counselor_id' => $con,
                'school_id' => $sch,
            ]);
            User::factory()->create([
                'username' => "siswa{$room->id}",
                'verified' => false,
                'role' => 'student',
                'mentor_id' => $men,
                'room_id' => $room->id,
                'counselor_id' => $con,
                'school_id' => $sch,
            ]);
            User::factory(8)->create([
                'verified' => true,
                'role' => 'student',
                'mentor_id' => $men,
                'room_id' => $room->id,
                'counselor_id' => $con,
                'school_id' => $sch,
            ]);
        }
    }
}
