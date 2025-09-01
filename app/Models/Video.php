<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $guarded = [];
    protected $hidden = ["created_at", "updated_at", "school_id"];
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'video_tag');
    }
    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
