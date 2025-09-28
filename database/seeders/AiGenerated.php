<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AiGenerated extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $sections = [
            'first' => [
                'length' => 5, // jumlah soal
                'choices' => ['A', 'B', 'C', 'D'],
            ],
            'third' => [
                'length' => 2, // panjang key = 4
                'choices' => ['A', 'B', 'C', 'D'],
            ],
        ];

        foreach ($sections as $section => $data) {
            $keys = $this->generateKeys($data['choices'], $data['length']);

            $rows = [];
            foreach ($keys as $key) {
                $rows[] = [
                    'section' => $section,
                    'key' => $key,
                    'answer' => null,
                ];
            }

            // Single insert untuk 1 section
            DB::table('ai_generated')->insert($rows);
        }

        $sections = [
            'first' => [
                'length' => 7, // jumlah soal
                'choices' => ['A', 'B', 'C', 'D'],
            ],
        ];
    }

    private function generateKeys(array $choices, int $length, string $prefix = ''): array
    {
        if ($length === 0) {
            return [$prefix];
        }

        $results = [];
        foreach ($choices as $choice) {
            $results = array_merge(
                $results,
                $this->generateKeys($choices, $length - 1, $prefix.$choice)
            );
        }

        return $results;
    }
}
