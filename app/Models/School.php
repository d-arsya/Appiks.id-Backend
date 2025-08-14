<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    /** @use HasFactory<\Database\Factories\SchoolFactory> */
    use HasFactory;
    protected $fillable = ["name", "address"];

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
    public function articles()
    {
        return $this->hasMany(Article::class);
    }
    public function videos()
    {
        return $this->hasMany(Video::class);
    }
}
