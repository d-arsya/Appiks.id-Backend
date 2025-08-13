<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoodRecord extends Model
{
    /** @use HasFactory<\Database\Factories\MoodRecordFactory> */
    use HasFactory;
    protected $guarded = [];
}
