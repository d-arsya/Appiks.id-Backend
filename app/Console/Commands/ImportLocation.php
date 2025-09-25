<?php

namespace App\Console\Commands;

use App\Models\Location;
use Illuminate\Console\Command;

class ImportLocation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:locations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import locations from a large SQL file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = public_path('database/seeder/location.sql');

        if (! file_exists($filePath)) {
            $this->error('SQL file not found!');

            return;
        }

        $handle = fopen($filePath, 'r');
        $batchSize = 4000; // Adjust batch size for performance
        $data = [];
        $loop = 1;
        $this->info("Import with batch size : $batchSize");

        while (($line = fgets($handle)) !== false) {
            // Match SQL insert values using regex
            if (preg_match_all('/\((.*?)\)/', $line, $matches)) {
                foreach ($matches[1] as $values) {
                    $valuesArray = explode(',', $values);
                    try {
                        $info = [
                            'postalcode' => trim(str_replace("'", '', $valuesArray[1])),
                            'village' => trim(str_replace("'", '', $valuesArray[2])),
                            'district' => trim(str_replace("'", '', $valuesArray[3])),
                            'city' => trim(str_replace("'", '', $valuesArray[4])),
                            'province' => trim(str_replace("'", '', $valuesArray[5])),
                        ];
                        if ($info['postalcode'] != '`postalcode`') {

                            $data[] = $info;
                        }
                    } catch (\Throwable $th) {
                        $this->info($th->getMessage());
                        $this->info(json_encode($valuesArray));
                    }
                }

                // Insert when batch size is reached
                if (count($data) >= $batchSize) {
                    Location::insert($data);
                    $data = []; // Reset batch
                    $this->info("Import #$loop successfully!");
                    $loop++;
                }
            }
        }

        // Insert any remaining data
        if (! empty($data)) {
            Location::insert($data);
        }
        Location::where('id', '>', '0')->update(['created_at' => now(), 'updated_at' => now()]);

        fclose($handle);
        $total = ($loop - 1) * $batchSize + count($data);
        $this->info("Import #$loop successfully!");
        $this->info("Import $total row");
    }
}
