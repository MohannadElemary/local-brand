<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class ProcessEmployeeCsv implements ShouldQueue
{
    use Dispatchable, Queueable;

    protected string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function handle(): void
    {
        $csv = Reader::createFromPath(Storage::disk('local')->path($this->path));

        $csv->setHeaderOffset(0);

        // Gather all records in this chunk file
        $records = iterator_to_array($csv->getRecords());

        // Dispatch a job to process this chunk of records
        ProcessEmployeeChunk::dispatch($records);

        // Delete the chunk file after processing
        Storage::disk('local')->delete($this->path);
    }
}
