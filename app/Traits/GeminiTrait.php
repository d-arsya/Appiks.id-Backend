<?php

namespace App\Traits;

use Gemini\Data\GenerationConfig;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\DB;

trait GeminiTrait
{
    protected function sendGemini(string $text, int $maxToken = 3000)
    {
        $currentToken = DB::table('gemini_api_token')->where('used', true)->first();
        config(['gemini.api_key' => $currentToken->token]);
        $geminiModel = Gemini::generativeModel('models/gemini-2.0-flash');
        $generationConfig = new GenerationConfig(maxOutputTokens: $maxToken);
        $result = $geminiModel->withGenerationConfig($generationConfig)->generateContent($text)->text();
        $tokenCount = $geminiModel->countTokens($text)->totalTokens;
        DB::table('gemini_api_token')
            ->where('id', $currentToken->id)
            ->update([
                'quota' => $currentToken->quota + $tokenCount,
                'used' => false,
            ]);
        $nextToken = DB::table('gemini_api_token')
            ->where('id', '>', $currentToken->id)
            ->orderBy('id')
            ->first()
            ?? DB::table('gemini_api_token')->orderBy('id')->first();

        DB::table('gemini_api_token')->where('id', $nextToken->id)->update(['used' => true]);

        return $result;
    }
}
