<?php

namespace App\Console\Commands;

use App\Traits\GeminiTrait;
use App\Traits\QuestionnaireTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateArchtype extends Command
{
    use GeminiTrait, QuestionnaireTrait;

    protected $signature = 'generate:archtype';

    protected $description = 'Get ai generated archtype persona';

    public function handle()
    {
        if (DB::table('ai_generated')->whereNull('answer')->count()) {
            $need_generates = DB::table('ai_generated')->whereNull('answer')->inRandomOrder()->first();
            $this->info('Get the null value');
        } else {
            $need_generates = DB::table('ai_generated')->orderBy('updated_at', 'asc')->inRandomOrder()->first();
            $this->info('Update the oldest value');
        }
        try {
            if (strlen($need_generates->key) == '5') {
                $this->info('Get first test');
                $this->firstInsecureTest(str_split($need_generates->key));
            } else {
                $this->info('Get third test');
                $this->thirdInsecureTest(str_split($need_generates->key));
            }
            $this->info('Finish');
        } catch (\Throwable $th) {
            $this->info($th->getMessage());
        }
    }
}
