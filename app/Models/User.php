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
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
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

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function moodStatuses()
    {
        return $this->hasMany(MoodStatus::class);
    }

    public function moodRecords()
    {
        return $this->hasMany(MoodRecord::class);
    }
    public function questionnaireAnswers()
    {
        return $this->hasMany(QuestionnaireAnswer::class);
    }
    public function schedule()
    {
        return $this->hasOne(Schedule::class);
    }
    public function meetStudent()
    {
        return $this->hasMany(Meet::class, 'student_id', 'id');
    }
    public function meetTeacher()
    {
        return $this->hasMany(Meet::class, 'teacher_id', 'id');
    }
}
