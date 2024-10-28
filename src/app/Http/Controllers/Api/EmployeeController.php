<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeResource;
use App\Services\EmployeeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class EmployeeController extends Controller
{
    protected EmployeeService $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $employees = $this->employeeService->index(
            $request->query('page', 1),
            $request->query('per_page', 15)
        );

        return EmployeeResource::collection($employees);
    }

    public function show(string $empId): JsonResponse
    {
        $employee = $this->employeeService->show($empId);

        if (!$employee) {
            return response()->json(['error' => 'Employee not found.'], Response::HTTP_NOT_FOUND);
        }

        return response()->json(new EmployeeResource($employee));
    }

    public function destroy(string $empId): JsonResponse
    {
        $deleted = $this->employeeService->destroy($empId);

        if (!$deleted) {
            return response()->json(['error' => 'Employee not found or could not be deleted.'], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['message' => 'Employee deleted successfully.']);
    }
}
