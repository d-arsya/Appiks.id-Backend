<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagResource;
use App\Models\Tag;
use App\Traits\ApiResponder;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;

class TagController extends Controller
{
    use ApiResponder;

    /**
     * Get all tags
     */
    #[Group('Content')]
    public function index()
    {
        $tags = Tag::all();
        return $this->success(TagResource::collection($tags));
    }
}
