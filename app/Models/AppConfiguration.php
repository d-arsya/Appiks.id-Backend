<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppConfiguration extends Model
{
    protected $table = 'app_configuration';

    public $timestamps = false;

    protected $guarded = [];

    public static function useGemini()
    {
        $configuration = AppConfiguration::where('key', 'GEMINI_TOKEN_USED');
        $used = $configuration->first()->value;

        return $used;
    }
}
