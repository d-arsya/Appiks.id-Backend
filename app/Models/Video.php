<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = ["updated_at", "school_id"];
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'video_tag');
    }
    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
