<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMeetRequest;
use App\Http\Resources\MeetResource;
use App\Models\Meet;
use App\Traits\ApiResponderTrait;
use Carbon\Carbon;
use Http\Discovery\Exception\NotFoundException;

class MeetController extends Controller
{
    use ApiResponderTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->success(MeetResource::collection(Meet::all()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateMeetRequest $request)
    {
        $payload = $request->validated();
        $payload["day"] = $this->getDateOfDay($payload["day"]);
        $meet = Meet::create($payload);
        return $this->success(new MeetResource($meet), 'Success', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $meet = Meet::find($id);
        if (!$meet) {
            throw new NotFoundException();
        }
        return $this->success($meet);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateMeetRequest $request, string $id)
    {
        $meet = Meet::find($id);
        if (!$meet) {
            throw new NotFoundException();
        }
        $meet->update($request->validated());
        return $this->success($meet);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $meet = Meet::find($id);
        if (!$meet) {
            throw new NotFoundException();
        }
        $meet->delete();
        return $this->success(null);
    }

    protected function getDateOfDay(string $day)
    {
        $dayNames = [
            'senin'  => 1, // Monday
            'selasa' => 2, // Tuesday
            'rabu'   => 3, // Wednesday
            'kamis'  => 4, // Thursday
            'jumat'  => 5, // Friday
            'sabtu'  => 6, // Saturday
            'minggu' => 0, // Sunday
        ];
        $targetDow = $dayNames[$day];
        $targetDate = now()->startOfWeek(Carbon::SUNDAY)->addDays($targetDow);
        if (now() > $targetDate) {
            $targetDate->addDays(7);
        }
        return $targetDate->toDateString();
    }
}
