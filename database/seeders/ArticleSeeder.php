<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\School;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = School::all();
        foreach ($schools as $item) {
            Article::factory(3)->create(["school_id" => $item->id]);
        }
    }
}
