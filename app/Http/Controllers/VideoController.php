<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateVideoRequest;
use App\Http\Resources\VideoResource;
use App\Models\Tag;
use App\Models\Video;
use App\Traits\ApiResponder;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;

class VideoController extends Controller
{
    use ApiResponder;
    /**
     * Get all video
     */
    #[Group('Content')]
    public function index()
    {
        $videos = Video::with('tags')->where('school_id', Auth::user()->school_id)->get();
        return $this->success(VideoResource::collection($videos));
    }
    /**
     * Get all video by tag
     */
    #[Group('Content')]
    public function getByTag(Tag $tag)
    {
        $videos = $tag->videos()->with('tags')->get();
        return $this->success(VideoResource::collection($videos));
    }

    /**
     * Create new video
     */
    #[Group('Content')]
    public function store(CreateVideoRequest $request)
    {
        $meta = $this->getVideoDetail($request->video_id);
        $data = $request->all();
        $tags = $data["tags"];
        unset($data["tags"]);
        $video = Video::create(array_merge($data, $meta));
        $video->tags()->sync($tags);
        $res = Video::with(['school', 'tags'])->where('id', $video->id)->first();
        return $this->success(new VideoResource($res));
    }
    /**
     * Update video
     */
    #[Group('Content')]
    public function update(Request $request, Video $video)
    {
        $request->validate([
            "tags" => "array",
            "tags.*" => "integer|exists:tags,id"
        ]);
        $video->tags()->sync($request->tags);
        $res = Video::with(['school', 'tags'])->where('id', $video->id)->first();
        return $this->success(new VideoResource($res));
    }

    /**
     * Delete video
     */
    #[Group('Content')]
    public function destroy(Video $video)
    {
        Gate::authorize('delete', $video);
        $video->delete();
        return $this->success(null);
    }

    private function getVideoDetail($id)
    {
        $html = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0',
        ])->get("https://www.youtube.com/watch?v=" . $id)->body();

        $data = [];

        // --- 1) Extract ytInitialPlayerResponse ---
        if (preg_match('/ytInitialPlayerResponse\s*=\s*({.*?});/s', $html, $m)) {
            $player = json_decode($m[1], true);

            if (!empty($player['videoDetails'])) {
                $video = $player['videoDetails'];
                $data = [
                    'video_id'      => $video['videoId'] ?? null,
                    'title'         => $video['title'] ?? null,
                    'description'   => $video['shortDescription'] ?? null,
                    'thumbnail'    => end($video['thumbnail']['thumbnails'])["url"] ?? [],
                    'duration'      => gmdate(($video['lengthSeconds'] >= 3600 ? "H:i:s" : "i:s"), $video['lengthSeconds']) ?? null, // in seconds
                    'channel'  => $video['author'] ?? null,
                    'views'  => $video['viewCount'] ?? null,
                ];
            }
        }

        return $data;
    }
}
