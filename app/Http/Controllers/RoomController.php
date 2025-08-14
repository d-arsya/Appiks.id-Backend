<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoomRequest;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use App\Traits\ApiResponderTrait;
use Http\Discovery\Exception\NotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    use ApiResponderTrait, AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Room::class, 'room');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->role == 'super') {
            $rooms = Room::all();
        } else {
            $rooms = $user->school->rooms;
        }
        return $this->success(RoomResource::collection($rooms));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRoomRequest $request)
    {
        $room = Room::create($request->validated());
        return $this->success(new RoomResource($room), 'Success', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        return $this->success($room);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateRoomRequest $request, Room $room)
    {
        $room->update($request->validated());
        return $this->success($room);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        $room->delete();
        return $this->success(null);
    }
}
