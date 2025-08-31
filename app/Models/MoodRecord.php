<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoodRecord extends Model
{
    /** @use HasFactory<\Database\Factories\MoodRecordFactory> */
    use HasFactory;

    protected $guarded = [];
    protected $hidden = ["created_at", "updated_at"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
