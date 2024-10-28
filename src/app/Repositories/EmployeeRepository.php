<?php

namespace App\Repositories;

use App\Models\Employee;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeRepository
{
    public function batchInsert(array $data): void
    {
        $mappedData = array_map(function ($item) {
            return [
                'emp_id' => $item['emp_id'],
                'name_prefix' => $item['name_prefix'],
                'first_name' => $item['first_name'],
                'middle_initial' => $item['middle_initial'],
                'last_name' => $item['last_name'],
                'gender' => $item['gender'],
                'email' => $item['e_mail'],
                'date_of_birth' => $item['date_of_birth'],
                'time_of_birth' => $item['time_of_birth'],
                'age_in_years' => $item['age_in_yrs_'],
                'date_of_joining' => $item['date_of_joining'],
                'age_in_company_years' => $item['age_in_company_years_'],
                'phone_no' => $item['phone_no_'],
                'place_name' => $item['place_name'],
                'county' => $item['county'],
                'city' => $item['city'],
                'zip' => $item['zip'],
                'region' => $item['region'],
                'username' => $item['user_name'],
            ];
        }, $data);

        Employee::insert($mappedData);
    }

    public function paginate(int $page, int $perPage): LengthAwarePaginator
    {
        return Employee::paginate($perPage, ['*'], 'page', $page);
    }

    public function show(string $empId): ?Employee
    {
        return Employee::where('emp_id', $empId)->first();
    }

    public function destroy(string $empId): bool
    {
        return (bool) Employee::where('emp_id', $empId)->delete();
    }
}
