<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeminiApi extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tokens = explode(',', env('GEMINI_API_KEYS'));
        foreach ($tokens as $token) {
            DB::table('gemini_api_token')->insert(['token' => $token, 'used' => false, 'quota' => 0]);
        }
        DB::table('gemini_api_token')
            ->where('id', DB::table('gemini_api_token')->first()->id)
            ->update(['used' => true]);
    }
}
