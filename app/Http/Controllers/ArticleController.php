<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\Tag;
use App\Traits\ApiResponder;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    use ApiResponder;
    /**
     * Get all articles
     * 
     * Mendapatkan semua data artikel di sekolah tersebut
     */
    #[Group('Content')]
    public function index()
    {
        $articles = Article::with('tags')->where('school_id', Auth::user()->school_id)->get();
        return $this->success(ArticleResource::collection($articles));
    }
    /**
     * Get all article by tag
     * 
     * Mendapatkan semua artikel dengan tag tertentu di sekolah tersebut. Menggunakan id dari Tag
     */
    #[Group('Content')]
    public function getByTag(Tag $tag)
    {
        $articles = $tag->articles()->with('tags')->where('school_id', Auth::user()->school_id)->get();
        return $this->success(ArticleResource::collection($articles));
    }

    /**
     * Create new article
     * 
     * Membuat sebuah artikel baru
     * @bodyParam tags array<int> optional Daftar ID tag. Contoh: [1, 2, 3]
     */
    #[Group('Content')]
    public function store(CreateArticleRequest $request)
    {
        $tags = $request->tags[0];
        $tags = array_map('intval', json_decode($tags));
        $data = $request->all();
        unset($data["tags"]);
        $path = $request->file('thumbnail')->store('thumbnails', 'public');
        $data['thumbnail'] = env('APP_URL') . Storage::url($path);
        $article = Article::create($data);
        $tags = Tag::whereIn('id', $tags)->pluck('id')->toArray();
        // return $tags;
        $article->tags()->sync($tags);
        $res = Article::with(['school', 'tags'])->where('id', $article->id)->first();
        return $this->success(new ArticleResource($res));
    }
    /**
     * Update article
     * 
     * Mengupdate tag yang dimiliki oleh artikel tersebut. Hanya bisa dilakukan oleh Admin TU di sekolah tersebut
     */
    #[Group('Content')]
    public function update(Request $request, Article $article)
    {
        Gate::authorize('update', $article);
        $request->validate([
            "tags" => "array",
            "tags.*" => "integer|exists:tags,id"
        ]);
        $article->tags()->sync($request->tags);
        $res = Article::with(['school', 'tags'])->where('id', $article->id)->first();
        return $this->success(new ArticleResource($res));
    }

    /**
     * Delete article
     * 
     * Menghapus konten artikel di sekolah tersebut. Hanya bisa dilakukan oleh Admin TU di sekolah tersebut.
     */
    #[Group('Content')]
    public function destroy(Article $article)
    {
        Gate::authorize('delete', $article);
        $article->delete();
        return $this->delete();
    }

    /**
     * Get article detail
     * 
     * Mendapatkan artikel detail berdasarkan slug
     */
    #[Group('Content')]
    public function getArticle(Article $article)
    {
        Gate::authorize('view', $article);
        return $this->success(new ArticleResource($article));
    }
}
