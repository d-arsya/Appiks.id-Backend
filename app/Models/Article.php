<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = ['updated_at', 'school_id'];

    protected $casts = [
        'content' => 'array',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'article_tag');
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
