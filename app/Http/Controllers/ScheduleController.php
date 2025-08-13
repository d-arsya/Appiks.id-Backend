<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateScheduleRequest;
use App\Http\Resources\ScheduleResource;
use App\Models\Schedule;
use App\Traits\ApiResponderTrait;
use Http\Discovery\Exception\NotFoundException;

class ScheduleController extends Controller
{
    use ApiResponderTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->success(ScheduleResource::collection(Schedule::all()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateScheduleRequest $request)
    {
        $schedule = Schedule::create($request->validated());
        return $this->success(new ScheduleResource($schedule), 'Success', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $schedule = Schedule::find($id);
        if (!$schedule) {
            throw new NotFoundException();
        }
        return $this->success($schedule);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateScheduleRequest $request, string $id)
    {
        $schedule = Schedule::find($id);
        if (!$schedule) {
            throw new NotFoundException();
        }
        $schedule->update($request->validated());
        return $this->success($schedule);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $schedule = Schedule::find($id);
        if (!$schedule) {
            throw new NotFoundException();
        }
        $schedule->delete();
        return $this->success(null);
    }
}
