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
        // $schools = School::all();
        // $rooms = Room::all();
        // $users = [];

        // // super admin
        // $users[] = User::factory()->raw([
        //     "email" => "super@super.com",
        //     "role" => "super",
        //     "room_id" => null,
        //     "school_id" => null
        // ]);

        // // admin, headteacher, conselor
        // foreach ($schools as $school) {
        //     $dom = $school->id . ".com";
        //     $users[] = User::factory()->raw([
        //         "email" => "admin@school" . $dom,
        //         "role" => "admin",
        //         "room_id" => null,
        //         "school_id" => $school->id
        //     ]);

        //     $users[] = User::factory()->raw([
        //         "email" => "headteacher@school" . $dom,
        //         "role" => "headteacher",
        //         "room_id" => null,
        //         "school_id" => $school->id
        //     ]);
        //     $users[] = User::factory()->raw([
        //         "email" => "conselor@school" . $dom,
        //         "role" => "conselor",
        //         "room_id" => null,
        //         "school_id" => $school->id
        //     ]);
        // }

        // // teacher + students
        // foreach ($rooms as $room) {
        //     $dom = "@room" . $room->id . ".com";
        //     $users[] = User::factory()->raw([
        //         "email" => "teacher" . $dom,
        //         "role" => "teacher",
        //         "room_id" => $room->id,
        //         "school_id" => $room->school_id
        //     ]);
        //     $users[] = User::factory()->raw([
        //         "email" => "student1" . $dom,
        //         "role" => "student",
        //         "room_id" => $room->id,
        //         "school_id" => $room->school_id
        //     ]);
        //     $users[] = User::factory()->raw([
        //         "email" => "student2" . $dom,
        //         "role" => "student",
        //         "room_id" => $room->id,
        //         "school_id" => $room->school_id
        //     ]);
        // }

        // Single bulk insert
        $users = $users = [
            [
                'name' => 'Prof. Franz Leuschke Jr.',
                'email' => 'teacher@room4.com',
                'phone' => '1-757-596-1359',
                'identifier' => 'a8c7f279-eb3a-352f-b433-8648da89ea36',
                'password' => '$2y$12$7qkkoqhjZMvlJATEIxYDg.703Cf0Fmx1Om.LugiBSayVcL0bsEbS.',
                'verified' => 1,
                'role' => 'teacher',
                'room_id' => 4,
                'school_id' => 2,
            ],
            [
                'name' => 'Mr. Scottie Schowalter Sr.',
                'email' => 'teacher@room3.com',
                'phone' => '+1.318.973.4731',
                'identifier' => '597bfd16-f14c-3030-960f-a994a707a7fa',
                'password' => '$2y$12$yy14tXgXhaEm0MT0IEPuVuhwHdI//mGy6Uoa287ILxtW7zbHubnfK',
                'verified' => 1,
                'role' => 'teacher',
                'room_id' => 3,
                'school_id' => 2,
            ],
            [
                'name' => 'Dr. Michael Bartoletti IV',
                'email' => 'teacher@room2.com',
                'phone' => '854-347-4056',
                'identifier' => 'e9405fc0-0f13-336c-bf0d-f2616afff242',
                'password' => '$2y$12$z47RoQtoXkAtOKc3unhMW.KS3id3tc/isP51SP.He7NcjCqCDfufy',
                'verified' => 1,
                'role' => 'teacher',
                'room_id' => 2,
                'school_id' => 1,
            ],
            [
                'name' => 'Prof. Raymundo Yundt',
                'email' => 'teacher@room1.com',
                'phone' => '1-724-691-6100',
                'identifier' => 'a399d32b-4dca-3195-a364-2b988001358f',
                'password' => '$2y$12$nKrB2LTL149Xc8yURiip7elltWb2UGrjrNeZ70T8GapGj7x7DSKhO',
                'verified' => 0,
                'role' => 'teacher',
                'room_id' => 1,
                'school_id' => 1,
            ],
            [
                'name' => 'Stefan Murazik',
                'email' => 'super@super.com',
                'phone' => '+1-239-883-2291',
                'identifier' => '95ecdef0-eb85-3f53-b6b2-00322ec63e59',
                'password' => '$2y$12$TIkC/HEiutOFTv8ccLKf8OiKYwYThYUdf97vX0OKsa8sVo2Fe6u1.',
                'verified' => 1,
                'role' => 'super',
                'room_id' => null,
                'school_id' => null,
            ],
            [
                'name' => 'Mrs. Bria Emard PhD',
                'email' => 'student2@room4.com',
                'phone' => '+1.689.995.5771',
                'identifier' => 'c55756a8-bc86-3d87-8d42-fa3905f5393e',
                'password' => '$2y$12$4npslXK4EMOkhUJqXE3tLuaZvr.uO8VyzHIfoosrkz2uYBStfueVW',
                'verified' => 1,
                'role' => 'student',
                'room_id' => 4,
                'school_id' => 2,
            ],
            [
                'name' => 'Hunter Pouros DDS',
                'email' => 'student2@room3.com',
                'phone' => '(585) 336-1557',
                'identifier' => '0ea06eee-8a56-3647-8326-7ea2ca28f536',
                'password' => '$2y$12$GzAo8ZUD9vAEEv6NVWA3au7WYO8VtlIUk5z5enrB3agjfZSCdNHQK',
                'verified' => 0,
                'role' => 'student',
                'room_id' => 3,
                'school_id' => 2,
            ],
            [
                'name' => 'Reggie Douglas',
                'email' => 'student2@room2.com',
                'phone' => '+1-828-344-7083',
                'identifier' => 'ec870db1-2003-3945-b06e-802f8936f8a8',
                'password' => '$2y$12$KGDh9WZ0.L7uPyv8BAAMd.EHSjWTko8ybkiW/RwzzpQaqbY19YRAm',
                'verified' => 0,
                'role' => 'student',
                'room_id' => 2,
                'school_id' => 1,
            ],
            [
                'name' => 'Mr. Tillman Dietrich',
                'email' => 'student2@room1.com',
                'phone' => '517-661-7469',
                'identifier' => '851eb6e8-b4e1-36bd-97b0-014a07d287f2',
                'password' => '$2y$12$o.mLevYCSaSUnVWQuBVe0e.CzrFOEpMYYj/z7OCbAaHn9tMETcBIq',
                'verified' => 1,
                'role' => 'student',
                'room_id' => 1,
                'school_id' => 1,
            ],
            [
                'name' => 'Chaya Grady DDS',
                'email' => 'student1@room4.com',
                'phone' => '(606) 409-5592',
                'identifier' => 'ec1fb9a8-2c88-3dda-9074-29001ff720a3',
                'password' => '$2y$12$JbSTHvibYA/rBYJ8axAhF.6UhHxI.ej0N/07kLPJciZAYLT2VeqfG',
                'verified' => 0,
                'role' => 'student',
                'room_id' => 4,
                'school_id' => 2,
            ],
            [
                'name' => 'Dr. Joaquin Walsh I',
                'email' => 'student1@room3.com',
                'phone' => '+1.478.706.8514',
                'identifier' => 'd9c0ffa6-c3aa-358c-833a-4a2421a11a1a',
                'password' => '$2y$12$GKonbrniZ6bMPGTO6TMRpuK7l3z2uZN2m5jeyRWkY0ZjGuDXRX0YS',
                'verified' => 1,
                'role' => 'student',
                'room_id' => 3,
                'school_id' => 2,
            ],
            [
                'name' => 'Ms. Lauryn Monahan I',
                'email' => 'student1@room2.com',
                'phone' => '+1 (205) 761-4955',
                'identifier' => '760ca57f-8096-30ee-ac1a-c6bcb0e1f976',
                'password' => '$2y$12$yE/MNB5dGwRgxt9g0GvX7ej7.t2YCQ1xkFwX38EBjeSR6ewYEJ3v6',
                'verified' => 1,
                'role' => 'student',
                'room_id' => 2,
                'school_id' => 1,
            ],
            [
                'name' => 'Roselyn Bashirian V',
                'email' => 'student1@room1.com',
                'phone' => '+1-520-203-7700',
                'identifier' => 'f5c87108-43ff-36a6-b8ad-e5d0d6e923f5',
                'password' => '$2y$12$2qt.bYIa9sd483cH7Ph0Se5fPuWtTVgQt7OcV31J6kddr97vL0zYS',
                'verified' => 0,
                'role' => 'student',
                'room_id' => 1,
                'school_id' => 1,
            ],
            [
                'name' => 'Alycia Gutkowski',
                'email' => 'headteacher@school2.com',
                'phone' => '207-842-4279',
                'identifier' => 'ece66a23-0eef-37d2-9a98-b21b3dc02cec',
                'password' => '$2y$12$VLZdTqz3B7b2.E9ia0OS8.25m3N02yL36DfWB2JF/aqA8SPUbnziy',
                'verified' => 1,
                'role' => 'headteacher',
                'room_id' => null,
                'school_id' => 2,
            ],
            [
                'name' => 'Brannon Reynolds',
                'email' => 'headteacher@school1.com',
                'phone' => '1-707-390-0208',
                'identifier' => '025ab97e-18eb-3b24-96bc-fd4fcc1a2015',
                'password' => '$2y$12$nORxLDLFvO7Wu706iLx7pekPpWZNnVwndKKg3BWE3wv9ctKDUzUU2',
                'verified' => 0,
                'role' => 'headteacher',
                'room_id' => null,
                'school_id' => 1,
            ],
            [
                'name' => 'Aglae Walsh III',
                'email' => 'conselor@school2.com',
                'phone' => '818.231.3188',
                'identifier' => 'ac9915b3-90e1-32f1-80ee-affa6d65877b',
                'password' => '$2y$12$6dOC2XxY.zM8DB4b0/3c6.E0SqzEiHeOCUyNqARpgr/6N1ZwNkFSO',
                'verified' => 0,
                'role' => 'conselor',
                'room_id' => null,
                'school_id' => 2,
            ],
            [
                'name' => 'Abby Bosco',
                'email' => 'conselor@school1.com',
                'phone' => '+1-269-663-3552',
                'identifier' => '873d572e-2c1b-3cfc-ba17-eda30ff5cdd1',
                'password' => '$2y$12$nza1aSs6X30aYq/TMIUvhOIQa1xnUB1weoq7bpB5IeUYUWVpsYGV.',
                'verified' => 1,
                'role' => 'conselor',
                'room_id' => null,
                'school_id' => 1,
            ],
            [
                'name' => 'Mr. Rickie Larkin',
                'email' => 'admin@school2.com',
                'phone' => '+1 (828) 649-9309',
                'identifier' => 'be0a5f56-f097-3b74-bfb3-82036e640a50',
                'password' => '$2y$12$9cjvSGXt3JYSg6wjLt7RaOci6MPmgd60g7kaEv38qcjPCfVK6zNoq',
                'verified' => 0,
                'role' => 'admin',
                'room_id' => null,
                'school_id' => 2,
            ],
            [
                'name' => 'Dr. Braden McDermott',
                'email' => 'admin@school1.com',
                'phone' => '202-497-9354',
                'identifier' => '6b423ed9-9a94-3d46-b2db-25dbac6d5e76',
                'password' => '$2y$12$ZVDYBzrG/uVbSuFrTfquXuYtodVJW2.bq/RCbbtLTwhGHj6GrZdu6',
                'verified' => 1,
                'role' => 'admin',
                'room_id' => null,
                'school_id' => 1,
            ],
        ];

        User::insert($users);
    }
}
