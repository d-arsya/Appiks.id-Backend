<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoomRequest;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use App\Models\User;
use App\Traits\ApiResponder;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class RoomController extends Controller
{
    use ApiResponder;

    /**
     * Get room count
     *
     * Digunakan untuk mendapatkan jumlah kelas didalam sekolah user tersebut. Bisa diakses oleh selain murid
     */
    #[Group('Room')]
    public function getRoomCount()
    {
        Gate::authorize('dashboard-data');
        $count = Room::where('school_id', Auth::user()->school_id)->count();

        return $this->success(['count' => (int) $count]);
    }

    /**
     * Get room and student count
     *
     * Digunakan untuk mendapatkan jumlah kelas dan siswa didalam sekolah user tersebut. Bisa diakses oleh selain murid
     */
    #[Group('Room')]
    public function roomStudentCount()
    {
        Gate::authorize('dashboard-data');
        $room = Room::where('school_id', Auth::user()->school_id)->count();
        $student = User::where('school_id', Auth::user()->school_id)->whereRole('student')->count();

        return $this->success(['student' => (int) $student, 'room' => (int) $room]);
    }

    /**
     * Get all rooms data
     *
     * Digunakan untuk mendapatkan jumlah kelas didalam sekolah user tersebut. Bisa diakses oleh selain murid
     */
    #[Group('Room')]
    public function index()
    {
        Gate::authorize('dashboard-data');
        $rooms = Room::where('school_id', Auth::user()->school_id)->get();

        return $this->success(RoomResource::collection($rooms));
    }

    /**
     * Create room
     *
     * Digunakan membuat kelas baru. Hanya bisa dilakukan oleh Admin TU
     */
    #[Group('Room')]
    public function store(CreateRoomRequest $request)
    {
        $room = Room::create($request->all());

        return $this->success(new RoomResource($room));
    }
}
