<?php

use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\EmployeeImportController;
use Illuminate\Support\Facades\Route;

Route::post('/employee', [EmployeeImportController::class, 'import']);

Route::get('/employee', [EmployeeController::class, 'index']);
Route::get('/employee/{empId}', [EmployeeController::class, 'show']);
Route::delete('/employee/{empId}', [EmployeeController::class, 'destroy']);
