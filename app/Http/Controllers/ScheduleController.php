<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateScheduleRequest;
use App\Http\Resources\ScheduleResource;
use App\Models\Schedule;
use App\Traits\ApiResponderTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    use ApiResponderTrait, AuthorizesRequests;
    public function __construct()
    {
        $this->authorizeResource(Schedule::class, 'schedule');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()->role == 'teacher') {
            $user = Auth::user()->school->conselors->pluck('id');
            return $this->success(ScheduleResource::collection(Schedule::with('conselor')->whereIn('user_id', $user)->get()));
        }
        return $this->success(new ScheduleResource(Auth::user()->schedule));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateScheduleRequest $request, Schedule $schedule)
    {
        $schedule->update($request->validated());
        return $this->success($schedule);
    }
}
