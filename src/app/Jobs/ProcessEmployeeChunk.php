<?php

namespace App\Jobs;

use App\Repositories\EmployeeRepository;
use App\Services\UserDataValidationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class ProcessEmployeeChunk implements ShouldQueue
{
    use Dispatchable, Queueable;

    protected array $chunk;
    protected int $batchSize = 50;

    public function __construct(array $chunk)
    {
        $this->chunk = $chunk;
    }

    public function handle(EmployeeRepository $employeeRepository, UserDataValidationService $validationService): void
    {
        $batchData = [];

        foreach ($this->chunk as $row) {
            // Validate Data
            $validatedData = $validationService->validate($row);

            // Skip if the employee has invalid data
            if (!$validatedData) {
                continue;
            }

            $batchData[] = $validatedData;

            if (count($batchData) === $this->batchSize) {
                // Insert in bulk
                $this->batchInsert($employeeRepository, $batchData);
                $batchData = [];
            }
        }

        // Insert any remaining data
        if (count($batchData) > 0) {
            $this->batchInsert($employeeRepository, $batchData);
        }
    }

    private function batchInsert(EmployeeRepository $employeeRepository, array $batchData): void
    {
        try {
            $employeeRepository->batchInsert($batchData);
        } catch (\Throwable $e) {
            Log::error("Batch insert error: " . $e->getMessage());
        }
    }
}
