<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Traits\ApiResponderTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    use ApiResponderTrait, AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Article::class, 'article');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Auth::user()->school->articles;
        return $this->success(ArticleResource::collection($articles));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateArticleRequest $request)
    {
        $article = Article::create($request->validated());
        return $this->success(new ArticleResource($article), 'Success', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        return $this->success($article);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateArticleRequest $request, Article $article)
    {
        $article->update($request->validated());
        return $this->success($article);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $article->delete();
        return $this->success(null);
    }
}
