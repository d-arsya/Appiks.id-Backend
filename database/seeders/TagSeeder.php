<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tag::insert([
            ["title" => "Self Awareness"],
            ["title" => "Mindfulness"],
            ["title" => "Mental Health"],
            ["title" => "Bullying"],
        ]);
    }
}
