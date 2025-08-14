<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionnaireAnswer extends Model
{
    /** @use HasFactory<\Database\Factories\QuestionnaireAnswerFactory> */
    use HasFactory;
    protected $guarded = [];
    protected $casts = ["answers" => "array"];

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
