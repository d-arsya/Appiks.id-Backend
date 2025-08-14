<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meet extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function anomaly()
    {
        return $this->belongsTo(Anomaly::class);
    }
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }
}
