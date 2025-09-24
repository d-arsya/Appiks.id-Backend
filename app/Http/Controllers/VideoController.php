<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateVideoRequest;
use App\Http\Resources\VideoResource;
use App\Models\Article;
use App\Models\Tag;
use App\Models\Video;
use App\Traits\ApiResponder;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;

class VideoController extends Controller
{
    use ApiResponder;

    /**
     * Get all contents
     *
     * Mendapatkan semua data video dan artikel di sekolah tersebut
     */
    #[Group('Content')]
    public function allContents()
    {
        $schoolId = Auth::user()->school_id;

        $videos = Video::with('tags')
            ->where('school_id', $schoolId)
            ->get()
            ->map(function ($v) {
                $v->content_type = 'video';

                return $v;
            });

        $articles = Article::with('tags')
            ->where('school_id', $schoolId)
            ->get()
            ->map(function ($a) {
                $a->content_type = 'article';

                return $a;
            });

        $contents = $videos->concat($articles);
        $contents = $contents->sortByDesc('created_at')->values();

        return $this->success($contents);
    }

    /**
     * Get latest 3 contents
     *
     * Mendapatkan 3 data video dan artikel terbaru di sekolah tersebut
     */
    #[Group('Content')]
    public function getLatestContent()
    {
        $schoolId = Auth::user()->school_id;

        // Query video
        $videosQuery = Video::select('id', 'title', 'created_at', DB::raw("'video' as type"))
            ->where('school_id', $schoolId);

        // Query artikel
        $articlesQuery = Article::select('id', 'title', 'created_at', DB::raw("'article' as type"))
            ->where('school_id', $schoolId)
            ->union($videosQuery);

        // Ambil 3 terbaru dari gabungan
        $contents = DB::table(DB::raw("({$articlesQuery->toSql()}) as combined"))
            ->mergeBindings($articlesQuery->getQuery())
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return $this->success($contents);
    }

    /**
     * Get today created content
     *
     * Mendapatkan jumlah konten yang dibuat hari ini
     */
    #[Group('Content')]
    public function getTodayContent()
    {
        $school = Auth::user()->school;
        $count = $school->videos()
            ->whereDate('created_at', now())
            ->count()
            + $school->articles()
                ->whereDate('created_at', now())
                ->count();

        return $this->success(['count' => (int) $count]);
    }

    /**
     * Get all video
     *
     * Mendapatkan semua data video di sekolah tersebut
     */
    #[Group('Video')]
    public function index()
    {
        $videos = Video::with('tags')->where('school_id', Auth::user()->school_id)->get();

        return $this->success(VideoResource::collection($videos));
    }

    /**
     * Get all video by tag
     *
     * Mendapatkan semua video dengan tag tertentu di sekolah tersebut. Menggunakan id dari Tag
     */
    #[Group('Video')]
    public function getByTag(Tag $tag)
    {
        $videos = $tag->videos()->with('tags')->where('school_id', Auth::user()->school_id)->get();

        return $this->success(VideoResource::collection($videos));
    }

    /**
     * Create new video
     *
     * Membuat sebuah video baru. Cukup mengirimkan ID youtube video dan sistem akan mengambil meta data yang dibutuhkan
     */
    #[Group('Video')]
    public function store(CreateVideoRequest $request)
    {
        $meta = $this->getVideoDetail($request->video_id);
        $data = $request->all();
        $tags = $data['tags'];
        unset($data['tags']);
        $video = Video::create(array_merge($data, $meta));
        $video->tags()->sync($tags);
        $res = Video::with(['school', 'tags'])->where('id', $video->id)->first();

        return $this->success(new VideoResource($res));
    }

    /**
     * Update video
     *
     * Mengupdate tag yang dimiliki oleh video tersebut. Hanya bisa dilakukan oleh Admin TU di sekolah tersebut
     */
    #[Group('Video')]
    public function update(Request $request, Video $video)
    {
        Gate::authorize('update', $video);
        $request->validate([
            'tags' => 'array',
            'tags.*' => 'integer|exists:tags,id',
        ]);
        $video->tags()->sync($request->tags);
        $res = Video::with(['school', 'tags'])->where('id', $video->id)->first();

        return $this->success(new VideoResource($res));
    }

    /**
     * Delete video
     *
     * Menghapus konten video di sekolah tersebut. Hanya bisa dilakukan oleh Admin TU di sekolah tersebut.
     */
    #[Group('Video')]
    public function destroy(Video $video)
    {
        Gate::authorize('delete', $video);
        $video->delete();

        return $this->delete();
    }

    /**
     * Get video detail
     *
     * Mendapatkan video detail berdasarkan ID Youtube
     */
    #[Group('Video')]
    public function getVideoDetailId(Video $video)
    {
        Gate::authorize('view', $video);
        $data = $this->getVideoDetail($video->video_id);
        $video->update($data);

        return $this->success(new VideoResource($video));
    }

    private function getVideoDetail($id)
    {
        $html = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0',
        ])->get('https://www.youtube.com/watch?v='.$id)->body();
        $data = [];

        // --- 1) Extract ytInitialPlayerResponse ---
        if (preg_match('/ytInitialPlayerResponse\s*=\s*({.*?});/s', $html, $m)) {
            $player = json_decode($m[1], true);

            if (! empty($player['videoDetails'])) {
                $video = $player['videoDetails'];
                $data = [
                    'video_id' => $video['videoId'] ?? null,
                    'title' => $video['title'] ?? null,
                    'description' => $video['shortDescription'] ?? null,
                    'thumbnail' => end($video['thumbnail']['thumbnails'])['url'] ?? [],
                    'duration' => gmdate(($video['lengthSeconds'] >= 3600 ? 'H:i:s' : 'i:s'), $video['lengthSeconds']) ?? null, // in seconds
                    'channel' => $video['author'] ?? null,
                    'views' => $video['viewCount'] ?? null,
                ];
            }
        }

        return $data;
    }
}
