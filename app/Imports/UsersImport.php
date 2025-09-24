<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Room;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue
{
    protected $mentors;
    protected $counselors;
    protected $rooms;
    protected $schoolId;
    protected $defaultPassword;
    protected $insertedUsers;

    public function __construct($schoolId)
    {
        // Preload teachers and rooms ONCE
        $this->mentors = User::where('role', 'teacher')->pluck('id', 'identifier')->toArray();
        $this->counselors = User::where('role', 'counselor')->pluck('id', 'identifier')->toArray();
        $this->rooms   = Room::pluck('id', 'code')->toArray();
        $this->schoolId = $schoolId;
        $this->defaultPassword = Hash::make(config('app.default_password'));
        $this->insertedUsers = collect();
    }

    public function collection(Collection $rows)
    {
        $users = [];
        foreach ($rows as $row) {
            if (empty($row['nis']) || empty($row['nama'])) {
                break;
            }
            $users[] = [
                'name'       => $row['nama'],
                'username'   => $row['nis'],
                'identifier' => $row['nis'],
                'mentor_id'  => $this->mentors[$row['nip_wali']],
                'counselor_id'  => $this->counselors[$row['nip_bk']],
                'room_id'    => $this->rooms[$row['kode_kelas']],
                'school_id'  => $this->schoolId,
                'password'   => $this->defaultPassword,
                'role'       => 'student',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('users')->insert($users);
    }

    public function chunkSize(): int
    {
        return 1500;
    }
}
