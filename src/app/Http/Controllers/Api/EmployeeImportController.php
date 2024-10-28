<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\EmployeeImportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmployeeImportController extends Controller
{
    protected EmployeeImportService $employeeImportService;
    public function __construct(EmployeeImportService $employeeImportService)
    {
        $this->employeeImportService = $employeeImportService;
    }

    public function import(Request $request): JsonResponse
    {
        $this->employeeImportService->importCsv($request->getContent());

        return response()->json(['message' => 'CSV import initiated'], Response::HTTP_ACCEPTED);
    }
}
