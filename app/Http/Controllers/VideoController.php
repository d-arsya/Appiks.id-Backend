<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateVideoRequest;
use App\Http\Resources\VideoResource;
use App\Models\Video;
use App\Traits\ApiResponderTrait;
use Http\Discovery\Exception\NotFoundException;

class VideoController extends Controller
{
    use ApiResponderTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->success(VideoResource::collection(Video::all()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateVideoRequest $request)
    {
        $video = Video::create($request->validated());
        return $this->success(new VideoResource($video), 'Success', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $video = Video::find($id);
        if (!$video) {
            throw new NotFoundException();
        }
        return $this->success($video);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateVideoRequest $request, string $id)
    {
        $video = Video::find($id);
        if (!$video) {
            throw new NotFoundException();
        }
        $video->update($request->validated());
        return $this->success($video);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $video = Video::find($id);
        if (!$video) {
            throw new NotFoundException();
        }
        $video->delete();
        return $this->success(null);
    }
}
