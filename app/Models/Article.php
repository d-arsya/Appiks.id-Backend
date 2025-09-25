<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = ['updated_at', 'school_id'];

    protected $casts = [
        'content' => 'array',
    ];

    protected static function booted()
    {
        static::deleting(function ($article) {
            $path = str_replace(env('APP_URL').'/storage/', '', $article->thumbnail);

            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        });
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'article_tag');
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
