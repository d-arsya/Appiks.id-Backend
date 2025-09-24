<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\School;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $school = School::first();

        $articles = Article::factory(10)->create([
            'school_id' => $school->id,
        ]);

        foreach ($articles as $article) {
            $article->tags()->attach(
                collect([1, 2, 3, 4])->random(rand(1, 3))
            );
        }
    }
}
