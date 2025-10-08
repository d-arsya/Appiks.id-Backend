<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateWithBackup extends Command
{
    protected $signature = 'migrate:fresh-backup';

    // Tabel yang ingin di-backup
    protected $protectedTables = [
        'ai_generated',
        'gemini_api_token',
    ];

    public function handle()
    {
        $this->info('Starting backup of protected tables...');

        foreach ($this->protectedTables as $table) {
            $backupFile = storage_path("app/backup_{$table}.json");
            file_put_contents($backupFile, ''); // reset file

            DB::table($table)->orderBy('id')->chunk(1000, function ($rows) use ($backupFile) {
                $json = $rows->toJson()."\n";
                file_put_contents($backupFile, $json, FILE_APPEND);
            });

            $this->info("Backed up table: {$table}");
        }

        $this->info('Backup completed. Running migrate:fresh...');

        // Jalankan migrate:fresh
        $this->call('migrate:fresh');

        $this->info('Migrate:fresh completed. Restore backup');

        foreach ($this->protectedTables as $table) {
            $backupFile = storage_path("app/backup_{$table}.json");
            $batchSize = 1000;

            $lines = file($backupFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $data = json_decode($line, true);
                for ($i = 0; $i < count($data); $i += $batchSize) {
                    DB::table($table)->insert(array_slice($data, $i, $batchSize));
                }
            }

            $this->info("Restored table: {$table}");
        }

        $this->info('Restore completed. Seed database');
        $this->call('db:seed');
        $this->info('All done!');
    }
}
