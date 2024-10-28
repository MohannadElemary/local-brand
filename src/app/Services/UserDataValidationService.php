<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class UserDataValidationService
{
    public function validate(array $data): ?array
    {
        // Normalize all keys: remove special characters, replace spaces with underscores, lowercase all keys
        $data = array_combine(
            array_map(fn($key) => strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', trim($key))), array_keys($data)),
            $data
        );

        // Define validation rules with normalized keys
        $rules = [
            'emp_id' => 'required|numeric|unique:employees,emp_id',
            'name_prefix' => 'required|string',
            'first_name' => 'required|string',
            'middle_initial' => 'required|string|size:1',
            'last_name' => 'required|string',
            'gender' => 'required|in:M,F',
            'e_mail' => 'required|email|unique:employees,email',
            'date_of_birth' => 'required|date_format:n/j/Y',
            'time_of_birth' => 'required|date_format:h:i:s A',
            'age_in_yrs_' => 'required|numeric',
            'date_of_joining' => 'required|date_format:n/j/Y',
            'age_in_company_years_' => 'required|numeric',
            'phone_no_' => 'required|regex:/^\d{3}-\d{3}-\d{4}$/',
            'place_name' => 'required|string',
            'county' => 'required|string',
            'city' => 'required|string',
            'zip' => 'required|numeric',
            'region' => 'required|string',
            'user_name' => 'required|unique:employees,username|alpha_dash|regex:/^[a-z]+$/',
        ];

        // Perform validation
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            Log::error("Validation failed for user with Emp ID {$data['emp_id']}: " . implode(", ", $validator->errors()->all()));

            return null;
        }

        // Validate username format
        $expectedUsername = strtolower(substr($data['first_name'], 0, 1) . $data['middle_initial'] . $data['last_name']);
        if ($data['user_name'] !== $expectedUsername) {
            Log::error("Invalid username format for Emp ID {$data['emp_id']}. Expected: {$expectedUsername}");
            return null;
        }

        // Convert dates and times to appropriate formats
        $data['date_of_birth'] = $this->formatDate($data['date_of_birth']);
        $data['date_of_joining'] = $this->formatDate($data['date_of_joining']);
        $data['time_of_birth'] = $this->formatTime($data['time_of_birth']);

        return $data;
    }

    private function formatDate(string $date): ?string
    {
        try {
            return Carbon::createFromFormat('m/d/Y', $date)->format('Y-m-d');
        } catch (\Exception) {
            return null;
        }
    }

    private function formatTime(string $time): ?string
    {
        try {
            return Carbon::createFromFormat('h:i:s A', $time)->format('H:i:s');
        } catch (\Exception) {
            return null;
        }
    }
}
