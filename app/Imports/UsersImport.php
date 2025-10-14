<?php

namespace App\Imports;

use App\Models\Room;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class UsersImport implements ShouldQueue, ToCollection, WithChunkReading, WithColumnFormatting, WithHeadingRow
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
        $this->rooms = Room::pluck('id', 'code')->toArray();
        $this->schoolId = $schoolId;
        $this->defaultPassword = Hash::make(config('app.default_password'));
        $this->insertedUsers = collect();
    }

    public function collection(Collection $rows)
    {
        $users = [];
        foreach ($rows as $row) {
            if (empty($row['nisn']) || empty($row['nama'])) {
                break;
            }
            $users[] = [
                'name' => $row['nama'],
                'username' => $row['nisn'],
                'identifier' => $row['nisn'],
                'mentor_id' => $this->mentors[$row['nip_wali']],
                'counselor_id' => $this->counselors[$row['nip_bk']],
                'room_id' => $this->rooms[$row['kode_kelas']],
                'school_id' => $this->schoolId,
                'password' => $this->defaultPassword,
                'role' => 'student',
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

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
        ];
    }
}
