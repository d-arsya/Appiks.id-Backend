<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class LowercaseUsername extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:lowercase-username';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();
        foreach ($users as $user) {
            $user->update(['username' => strtolower($user->username)]);
        }
    }
}
