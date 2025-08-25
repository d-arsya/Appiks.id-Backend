<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $hidden = ["created_at", "updated_at"];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function admins()
    {
        return $this->hasMany(User::class)->where('role', 'admin');
    }
    public function headteacher()
    {
        return $this->hasOne(User::class)->where('role', 'headteacher');
    }
    public function conselors()
    {
        return $this->hasMany(User::class)->where('role', 'conselor');
    }
    public function teachers()
    {
        return $this->hasMany(User::class)->where('role', 'teacher');
    }
    public function students()
    {
        return $this->hasMany(User::class)->where('role', 'student');
    }
}
