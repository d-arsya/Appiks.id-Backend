<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    protected $hidden = [
        'id',
        'password',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'verified' => 'boolean',
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function mood()
    {
        return $this->hasMany(MoodRecord::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function mentor()
    {
        return $this->hasOne(User::class, 'id', 'mentor_id');
    }

    public function counselor()
    {
        return $this->hasOne(User::class, 'id', 'counselor_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function counselored()
    {
        return $this->hasMany(User::class, 'counselor_id', 'id')->where('role', 'student');
    }

    public function mentored()
    {
        return $this->hasMany(User::class, 'mentor_id', 'id')->where('role', 'student');
    }

    public function sharing()
    {
        return $this->hasMany(Sharing::class);
    }

    public function report()
    {
        return $this->hasMany(Report::class);
    }

    public function lastmood()
    {
        return $this->mood()->whereRecorded(now()->toDateString())->first()?->status;
    }

    public function lastmoodres()
    {
        return $this->hasOne(MoodRecord::class)->whereRecorded(now()->toDateString());
    }
}
