<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMoodRecordRequest;
use App\Http\Resources\MoodRecordResource;
use App\Models\MoodRecord;
use App\Traits\ApiResponderTrait;
use Http\Discovery\Exception\NotFoundException;

class MoodRecordController extends Controller
{
    use ApiResponderTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->success(MoodRecordResource::collection(MoodRecord::all()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateMoodRecordRequest $request)
    {
        $record = MoodRecord::create($request->validated());
        return $this->success(new MoodRecordResource($record), 'Success', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $record = MoodRecord::find($id);
        if (!$record) {
            throw new NotFoundException();
        }
        return $this->success($record);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateMoodRecordRequest $request, string $id)
    {
        $record = MoodRecord::find($id);
        if (!$record) {
            throw new NotFoundException();
        }
        $record->update($request->validated());
        return $this->success($record);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $record = MoodRecord::find($id);
        if (!$record) {
            throw new NotFoundException();
        }
        $record->delete();
        return $this->success(null);
    }
}
