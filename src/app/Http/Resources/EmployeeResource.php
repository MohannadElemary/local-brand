<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'emp_id' => $this->emp_id,
            'name_prefix' => $this->name_prefix,
            'first_name' => $this->first_name,
            'middle_initial' => $this->middle_initial,
            'last_name' => $this->last_name,
            'gender' => $this->gender,
            'email' => $this->email,
            'date_of_birth' => $this->date_of_birth,
            'time_of_birth' => $this->time_of_birth,
            'age_in_years' => $this->age_in_years,
            'date_of_joining' => $this->date_of_joining,
            'age_in_company_years' => $this->age_in_company_years,
            'phone_no' => $this->phone_no,
            'place_name' => $this->place_name,
            'county' => $this->county,
            'city' => $this->city,
            'zip' => $this->zip,
            'region' => $this->region,
            'username' => $this->username,
        ];
    }
}
