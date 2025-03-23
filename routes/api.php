<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\TeamController;
use App\Http\Controllers\Api\v1\ReportController;
use App\Http\Controllers\Api\v1\EmployeeController;
use App\Http\Controllers\Api\v1\Auth\AuthController;
use App\Http\Controllers\Api\v1\OrganizationController;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {

        Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
        Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/me', [AuthController::class, 'me'])->name('auth.me');
            Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
        });
    });
    Route::middleware('auth:sanctum')->group(function () {

        Route::get('organizations', [OrganizationController::class, 'index']);
        Route::post('organization', [OrganizationController::class, 'store']);
        Route::patch('organization/{id}', [OrganizationController::class, 'update']);
        Route::get('organization/{id}', [OrganizationController::class, 'show']);
        Route::delete('organization/{id}', [OrganizationController::class, 'destroy']);


        Route::get('teams', [TeamController::class, 'index']);
        Route::get('team/{id}', [TeamController::class, 'show']);
        Route::post('team', [TeamController::class, 'store']);
        Route::patch('team/{id}', [TeamController::class, 'update']);
        Route::delete('team/{id}', [TeamController::class, 'destroy']);



        Route::get('employees', [EmployeeController::class, 'index']);
        Route::get('employee/{id}', [EmployeeController::class, 'show']);
        Route::post('employee', [EmployeeController::class, 'store']);
        Route::patch('employee/{id}', [EmployeeController::class, 'update']);
        Route::delete('employee/{id}', [EmployeeController::class, 'destroy']);



        // Report endpoints
        Route::prefix('reports')->group(function () {
            Route::get('teams/salary', [ReportController::class, 'avg_salery_per_team'])
                ->name('teams.salary');

            // Organization reports
            Route::get('organizations/headcount', [ReportController::class, 'employess_per_organization'])->name('organizations.headcount');

            // Route::get('organizations/{id}/detail', [ReportController::class, 'employess_per_organization'])
            //     ->name('organizations.detail');
        });
    });
});
