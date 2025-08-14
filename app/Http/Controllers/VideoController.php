<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateVideoRequest;
use App\Http\Resources\VideoResource;
use App\Models\Video;
use App\Traits\ApiResponderTrait;
use Http\Discovery\Exception\NotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    use ApiResponderTrait, AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Video::class, 'video');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $videos = Auth::user()->school->videos;
        return $this->success(VideoResource::collection($videos));
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
    public function show(Video $video)
    {
        return $this->success($video);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateVideoRequest $request, Video $video)
    {
        $video->update($request->validated());
        return $this->success($video);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Video $video)
    {
        $video->delete();
        return $this->success(null);
    }
}
