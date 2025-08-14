<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
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
