<?php

namespace App\Http\Controllers;

use App\Traits\GeminiTrait;
use Gemini\Data\GenerationConfig;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\DB;

class StudentAnalyzer
{
    use GeminiTrait;

    public static function analyzeInsecureQuiz(): string
    {
        $archtype_data = [
            'SAGE' => [
                'archtype' => 'the sage',
                'arctype_char' => 'mental baja',
                'arctype_habits' => 'kebebasan dan kreasi',
                'arctype_description' => 'Kekuatan Supermu adalah Rasa Ingin Tahu! Otakmu adalah senjatanya. Kamu adalah pembelajar sejati yang bersemangat saat menemukan hal baru dan memahami bagaimana sesuatu bekerja. Dunia need people like you!',
                'archtype_power' => 'Rasa Ingin Tahu yang Tak Terkalahkan',
            ],
        ];

        $learning_mode_data = [
            'VISUAL' => [
                'style' => 'visual',
                'desc' => 'Kamu jago banget kalau belajar pakai mind map dan gambar!',
            ],
        ];

        $mindset_data = [
            'GROWTH' => 'Kamu punya motivasi internal dan resilience yang keren!',
            // ... Mindset lain (Fixed)
        ];
        $archetype_result = 'SAGE';
        $learning_mode_result = 'VISUAL';
        $mindset_result = 'GROWTH';
        $hardcoded_profile = $archtype_data[$archetype_result];
        $hardcoded_profile['learning_mode'] = $learning_mode_data[$learning_mode_result];
        $hardcoded_profile['fuel'] = $mindset_data[$mindset_result];
        $prompt_template = "Anda adalah generator pesan motivasi persona siswa. Siswa ini memiliki profil:
- Archtype: {$hardcoded_profile['archtype']} (Kekuatan: {$hardcoded_profile['archtype_power']})
- Mental: {$hardcoded_profile['arctype_char']}
- Gaya Belajar: {$hardcoded_profile['learning_mode']['style']} ({$hardcoded_profile['learning_mode']['desc']})

Buatkan 3 bagian teks yang unik dan sangat personal dalam format JSON (JANGAN gunakan Markdown atau pemformatan lain di output utama Anda, hanya JSON murni):
{
  \"personal_message\": \"Pesan motivasi yang mengikat Archtype dan Karakter Mental dalam 2-3 kalimat.\",
  \"mission_first\": \"Misi tantangan yang mengikat Kekuatan Super dan Gaya Belajar. Harus berupa tindakan belajar konkret dalam 1 kalimat.\",
  \"mission_second\": \"Misi tantangan yang mengasah Mental Baja/Resiliensi saat menghadapi kegagalan, dalam 1-2 kalimat.\"
}";
        // return $prompt_template;
        // Panggil Model AI
        $currentToken = DB::table('gemini_api_token')->where('used', true)->first();
        config(['gemini.api_key' => $currentToken->token]);
        $geminiModel = Gemini::generativeModel('models/gemini-2.0-flash');
        $generationConfig = new GenerationConfig(maxOutputTokens: 6000);
        $ai_results = $geminiModel->withGenerationConfig($generationConfig)->generateContent($prompt_template)->text();
        $final_result = [
            'archtype' => $hardcoded_profile['archtype'],
            'arctype_char' => $hardcoded_profile['arctype_char'],
            'arctype_habits' => $hardcoded_profile['arctype_habits'],
            'arctype description' => $hardcoded_profile['arctype_description'],
            'archtype_power' => $hardcoded_profile['archtype_power'],
            'learning_mode' => $hardcoded_profile['learning_mode'],
            'fuel' => $hardcoded_profile['fuel'],
            'personal_message' => $ai_results['personal_message'] ?? 'AI message generation failed.',
            'mission_first' => $ai_results['mission_first'] ?? 'AI mission 1 generation failed.',
            'mission_second' => $ai_results['mission_second'] ?? 'AI mission 2 generation failed.',
        ];

        return json_encode($final_result, JSON_PRETTY_PRINT);
    }
}
