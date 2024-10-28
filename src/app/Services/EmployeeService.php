<?php

namespace App\Services;

use App\Models\Employee;
use App\Repositories\EmployeeRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeService
{
    protected EmployeeRepository $employeeRepository;

    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    public function index(int $page = 1, int $perPage = 15): LengthAwarePaginator
    {
        return $this->employeeRepository->paginate($page, $perPage);
    }

    public function show(string $empId): ?Employee
    {
        return $this->employeeRepository->show($empId);
    }

    public function destroy(string $empId): bool
    {
        return $this->employeeRepository->destroy($empId);
    }
}
