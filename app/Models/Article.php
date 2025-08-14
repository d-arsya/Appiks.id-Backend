<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    /** @use HasFactory<\Database\Factories\ArticleFactory> */
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'tags' => 'array',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
