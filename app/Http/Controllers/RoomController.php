<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoomRequest;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use App\Models\User;
use App\Traits\ApiResponder;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
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
        if (Auth::user()->role == 'super') {
            $rooms = Room::with('school')->withCount('students')->get();
        } else {
            $rooms = Room::with('school')->withCount('students')->where('school_id', Auth::user()->school_id)->get();
        }

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

    /**
     * Delete room
     *
     * Digunakan menghapus kelas. Hanya bisa dilakukan oleh Admin TU
     */
    #[Group('Room')]
    public function destroy(Request $request, Room $room)
    {
        Gate::allowIf(function (User $user) use ($room) {
            return $user->role == 'admin' && $user->school_id == $room->school_id;
        });
        $data = $room->toArray();

        return $this->success($data);
    }

    /**
     * Update room
     *
     * Digunakan mengubah data kelas. Hanya bisa dilakukan oleh Admin TU
     */
    #[Group('Room')]
    public function update(Request $request, Room $room)
    {
        Gate::allowIf(function (User $user) use ($room) {
            return $user->role == 'admin' && $user->school_id == $room->school_id;
        });
        $request->validate([
            'name' => 'required|string',
            'level' => 'required|string|in:X,XI,XII',
        ]);
        $room->update($request->all());

        return $this->success(new RoomResource($room));
    }
}
