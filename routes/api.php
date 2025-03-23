<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\TeamController;
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
        Route::post('organization-store', [OrganizationController::class, 'store']);
        Route::post('organization-update/{id}', [OrganizationController::class, 'update'])->name('organization.update');
        Route::get('organizations', [OrganizationController::class, 'index']);
        Route::get('organization-show/{id}', [OrganizationController::class, 'show']);
        Route::post('organization-delete/{id}', [OrganizationController::class, 'destroy']);


        Route::get('teams', [TeamController::class, 'index']);
        Route::get('team/{id}', [TeamController::class, 'show']);
        Route::post('team', [TeamController::class, 'store']);
        Route::post('team/{id}', [TeamController::class, 'store']);
        Route::delete('team/{id}', [TeamController::class, 'destroy']);



        Route::get('employees', [EmployeeController::class, 'index']);
        Route::get('employee/{id}', [EmployeeController::class, 'show']);
        Route::post('employee', [EmployeeController::class, 'save']);
        Route::put('employees/{id}', [EmployeeController::class, 'save']);
        Route::delete('employees/{id}', [EmployeeController::class, 'destroy']);
    });
});
