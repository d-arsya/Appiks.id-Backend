<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    protected $hidden = ["id"];
    protected function casts(): array
    {
        return [
            'answers' => 'array',
            'order' => 'integer'
        ];
    }
}
