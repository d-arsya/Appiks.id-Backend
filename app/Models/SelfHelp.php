<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelfHelp extends Model
{
    use HasFactory;

    protected $table = 'self_helps';

    protected $guarded = [];

    protected function casts()
    {
        return [
            'content' => 'json',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
