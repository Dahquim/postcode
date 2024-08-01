<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class FetchPostcodeData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-postcode-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch UK postcode data and store it in the database';

    public function handle()
    {
        $url = 'https://parlvid.mysociety.org/os/ONSPD/2022-11.zip';
        $destinationPath = 'postcode-data/postcode.zip';

        try {
            $response = Http::get($url);
            if ($response->successful()) {
                Storage::put($destinationPath, $response->body());
                $this->info('Postcode data downloaded successfully.');

                $this->unzipFile(Storage::path($destinationPath), Storage::path('postcode-data'));
                $this->processCsv(Storage::path('postcode-data'));

            } else {
                $this->error('Failed to download postcode data.');
            }
        } catch (\Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
        }

        return 0;
    }

    protected function unzipFile($file, $destinationPath): void
    {
        $zip = new \ZipArchive;
        if ($zip->open($file) === TRUE) {
            $zip->extractTo($destinationPath);
            $zip->close();
            $this->info("\nFile unzipped successfully.");
            $files = Storage::files('postcode-data');
            foreach ($files as $extractedFile) {
                $this->info("\nExtracted file: $extractedFile");
            }
        } else {
            $this->error("\nFailed to unzip the file.");
        }
    }
    protected function processCsv($path): void
    {
        $csvFile = $path . '/Data/ONSPD_NOV_2022_UK.csv';
        $csv = Reader::createFromPath($csvFile, 'r');
        $csv->setHeaderOffset(0);

        $records = $csv->getRecords();
        $batchSize = 1000;
        $batch = [];

        $progressBar = new ProgressBar($this->output, iterator_count($records));
        $progressBar->start();

        foreach ($records as $record) {
            $batch[] = [
                'postcode' => $record['pcd'],
                'country' => $record['oscty'],
                'latitude' => $record['lat'],
                'longitude' => $record['long'],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($batch) === $batchSize) {
                DB::table('postcodes')->insert($batch);
                $batch = [];
            }
            $progressBar->advance();
        }
        if (!empty($batch)) {
            DB::table('postcodes')->insert($batch);
        }

        $progressBar->finish();
        $this->info("\nCSV file {$csvFile} processed successfully.");
    }
}
