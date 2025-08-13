<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anomaly extends Model
{
    /** @use HasFactory<\Database\Factories\AnomalyFactory> */
    use HasFactory;
    protected $guarded = [];
}
