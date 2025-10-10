<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateVideoRequest;
use App\Http\Resources\VideoResource;
use App\Models\Article;
use App\Models\Tag;
use App\Models\Video;
use App\Traits\ApiResponder;
use DateInterval;
use Dedoc\Scramble\Attributes\Group;
use Exception;
use Google\Client;
use Google\Service\YouTube;
use Google_Service_Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

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
        try {
            $video = Video::create(array_merge($data, $meta));
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 400, array_merge($data, $meta));
        }
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
     * Menghapus konten video di sekolah tersebut berdasarkan Video YT ID. Hanya bisa dilakukan oleh Admin TU di sekolah tersebut.
     */
    #[Group('Video')]
    public function destroy(string $video)
    {
        $video = Video::whereVideoId($video)->first();
        Gate::authorize('delete', $video);
        $data = $video->toArray();
        $video->delete();

        return $this->delete($data);
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

    public function getVideoDetail($videoId)
    {
        $client = new Client;
        $client->setDeveloperKey(env('YOUTUBE_API_KEY'));

        $youtube = new YouTube($client);

        try {
            $response = $youtube->videos->listVideos('snippet,contentDetails,statistics', [
                'id' => $videoId,
            ]);

            if (count($response->getItems()) === 0) {
                return null;
            }

            $video = $response->getItems()[0];

            $snippet = $video->getSnippet();
            $contentDetails = $video->getContentDetails();
            $statistics = $video->getStatistics();

            $duration = new DateInterval($contentDetails->getDuration());
            $seconds = ($duration->h * 3600) + ($duration->i * 60) + $duration->s;

            return [
                'video_id' => $videoId,
                'title' => $snippet->getTitle(),
                'description' => $snippet->getDescription(),
                'thumbnail' => $snippet->getThumbnails()->getDefault()->getUrl(),
                'duration' => gmdate(($seconds >= 3600 ? 'H:i:s' : 'i:s'), $seconds),
                'channel' => $snippet->getChannelTitle(),
                'views' => $statistics->getViewCount(),
            ];
        } catch (Google_Service_Exception $e) {
            return $this->error('YouTube API Error: '.$e->getMessage());

            return null;
        } catch (Exception $e) {
            return $this->error('General Error: '.$e->getMessage());
        }
    }
}
