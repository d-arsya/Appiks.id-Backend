<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'days' => 'array',
    ];

    public function conselor()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
