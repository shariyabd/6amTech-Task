<?php

use App\Enums\RoleType;
use Illuminate\Http\Request;
use App\Models\ImportStatistic;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\TeamController;
use App\Http\Controllers\Api\v1\ReportController;
use App\Http\Controllers\Api\v1\EmployeeController;
use App\Http\Controllers\Api\v1\Auth\AuthController;
use App\Http\Controllers\Api\v1\OrganizationController;
use App\Http\Controllers\Api\v1\EmployeeImportController;
use App\Http\Controllers\Api\v1\ImportStatisticController;
use App\Http\Controllers\Api\v1\SaleryUpdateLogController;

Route::prefix('v1')->group(function () {

    //auth routes
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
        Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/me', [AuthController::class, 'me'])->name('auth.me');
            Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
        });
    });

    Route::middleware('auth:sanctum')->group(function () {

        Route::middleware('role:' . RoleType::ADMIN->value)->group(function () {

            //Organizations
            Route::get('organizations', [OrganizationController::class, 'index']);
            Route::post('organization', [OrganizationController::class, 'store']);
            Route::patch('organization/{id}', [OrganizationController::class, 'update']);
            Route::get('organization/{id}', [OrganizationController::class, 'show']);
            Route::delete('organization/{id}', [OrganizationController::class, 'destroy']);

            // Admin only - Team create, update, delete operations
            Route::post('team', [TeamController::class, 'store']);
            Route::patch('team/{id}', [TeamController::class, 'update']);
            Route::delete('team/{id}', [TeamController::class, 'destroy']);

            // Reports
            Route::prefix('reports')->group(function () {
                Route::get('teams/salary', [ReportController::class, 'avg_salery_per_team'])->name('teams.salary');
                Route::get('organizations/headcount', [ReportController::class, 'employess_per_organization'])->name('organizations.headcount');
                Route::get('employee', [ReportController::class, 'employee_report'])->name('employee.report');
            });

            //statistics of import data
            Route::get('import-statistics', [ImportStatisticController::class, 'index']);
            //salery log report
            Route::get('salery-log', [SaleryUpdateLogController::class, 'index']);


            // empolyee json import
            Route::post('/employees/import', [EmployeeImportController::class, 'processImport']);
            Route::get('/employees/import/status/{id}', [EmployeeImportController::class, 'showStatus'])->name('import.status');
        });


        // Routes accessible by both Admin and Manager
        Route::middleware('role:' . RoleType::ADMIN->value . ',' . RoleType::MANAGER->value)->group(function () {

            // generete json employess data
            Route::get('/import', function () {
                return  generateSampleEmployeeData(300, 'exports/employee_data.json');
            });

            // Team view (both admin and manager can view)
            Route::get('teams', [TeamController::class, 'index']);
            Route::get('team/{id}', [TeamController::class, 'show']);

            // Employee  (both admin and manager have full access)
            Route::get('employees', [EmployeeController::class, 'index']);
            Route::get('employee/{id}', [EmployeeController::class, 'show']);
            Route::post('employee', [EmployeeController::class, 'store']);
            Route::patch('employee/{id}', [EmployeeController::class, 'update']);
            Route::delete('employee/{id}', [EmployeeController::class, 'destroy']);
        });
    });
});
