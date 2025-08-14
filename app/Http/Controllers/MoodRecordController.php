<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMoodRecordRequest;
use App\Http\Resources\MoodRecordResource;
use App\Models\MoodRecord;
use App\Traits\ApiResponderTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class MoodRecordController extends Controller
{
    use ApiResponderTrait, AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(MoodRecord::class, 'mood_record');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $records = Auth::user()->moodRecords;
        return $this->success(MoodRecordResource::collection($records));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateMoodRecordRequest $request)
    {
        $payload = $request->validated();
        $payload["user_id"] = Auth::user()->id;
        $moodRecord = MoodRecord::create($payload);
        return $this->success(new MoodRecordResource($moodRecord), 'Success', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MoodRecord $moodRecord)
    {
        return $this->success($moodRecord);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateMoodRecordRequest $request, MoodRecord $moodRecord)
    {
        $moodRecord->update($request->validated());
        return $this->success($moodRecord);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MoodRecord $moodRecord)
    {
        $moodRecord->delete();
        return $this->success(null);
    }
}
