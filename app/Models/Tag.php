<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $hidden = ['pivot'];
    public function videos()
    {
        return $this->belongsToMany(Video::class, 'video_tag');
    }
}
