<?php

namespace Database\Seeders;

use App\Models\Quote;
use App\Models\School;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuotesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = School::all();
        foreach ($schools as $item) {
            Quote::factory(10)->create(["school_id" => $item->id]);
        }
    }
}
