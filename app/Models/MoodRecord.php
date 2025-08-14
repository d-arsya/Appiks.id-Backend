<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoodRecord extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function moodStatuses()
    {
        return $this->belongsTo(MoodStatus::class, 'mood_status_id', 'id');
    }
}
