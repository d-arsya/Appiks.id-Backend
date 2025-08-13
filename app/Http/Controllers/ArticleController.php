<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Traits\ApiResponderTrait;
use Http\Discovery\Exception\NotFoundException;

class ArticleController extends Controller
{
    use ApiResponderTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->success(ArticleResource::collection(Article::all()));
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
    public function show(string $id)
    {
        $article = Article::find($id);
        if (!$article) {
            throw new NotFoundException();
        }
        return $this->success($article);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateArticleRequest $request, string $id)
    {
        $article = Article::find($id);
        if (!$article) {
            throw new NotFoundException();
        }
        $article->update($request->validated());
        return $this->success($article);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $article = Article::find($id);
        if (!$article) {
            throw new NotFoundException();
        }
        $article->delete();
        return $this->success(null);
    }
}
