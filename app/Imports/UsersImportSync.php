<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Room;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImportSync implements ToCollection, WithHeadingRow
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
        $this->defaultPassword = Hash::make('password');
        $this->insertedUsers = collect();
    }

    public function collection(Collection $rows)
    {
        logs()->info($rows);
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
        $nisValues = collect($users)->pluck('identifier');
        $this->insertedUsers = User::whereIn('identifier', $nisValues)->get();
    }
    public function getInsertedUsers()
    {
        return $this->insertedUsers;
    }
}
