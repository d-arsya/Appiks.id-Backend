<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoomRequest;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use App\Traits\ApiResponderTrait;
use Http\Discovery\Exception\NotFoundException;

class RoomController extends Controller
{
    use ApiResponderTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->success(RoomResource::collection(Room::all()));
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
    public function show(string $id)
    {
        $room = Room::find($id);
        if (!$room) {
            throw new NotFoundException();
        }
        return $this->success($room);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateRoomRequest $request, string $id)
    {
        $room = Room::find($id);
        if (!$room) {
            throw new NotFoundException();
        }
        $room->update($request->validated());
        return $this->success($room);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $room = Room::find($id);
        if (!$room) {
            throw new NotFoundException();
        }
        $room->delete();
        return $this->success(null);
    }
}
