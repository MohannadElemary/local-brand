<?php

namespace App\Services;

use App\Jobs\ProcessEmployeeCsv;
use Illuminate\Support\Facades\Storage;

class EmployeeImportService
{
    public function importCsv(string $csvContent): void
    {
        $lines = explode(PHP_EOL, $csvContent);
        $header = array_shift($lines);

        $chunkSize = 500;
        $chunks = array_chunk($lines, $chunkSize);

        foreach ($chunks as $index => $chunk) {
            $chunkFilePath = "temp/import_chunk_{$index}.csv";

            Storage::disk('local')->put($chunkFilePath, $header . PHP_EOL . implode(PHP_EOL, $chunk));

            ProcessEmployeeCsv::dispatch($chunkFilePath);
        }
    }
}
