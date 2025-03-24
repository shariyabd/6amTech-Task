<?php

namespace App\Providers;

use App\Listeners\LogSalaryChange;
use App\Services\PerformanceMonitor;
use App\Events\EmployeeSalaryUpdated;
use Illuminate\Support\Facades\Event;
use App\Listeners\QueueEmployeeImport;
use App\Events\EmployeeImportCompleted;
use App\Events\EmployeeImportRequested;
use Illuminate\Support\ServiceProvider;
use App\Listeners\HandleImportCompletion;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(PerformanceMonitor::class, function () {
            return new PerformanceMonitor();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(EmployeeImportRequested::class, QueueEmployeeImport::class);
        Event::listen(EmployeeImportCompleted::class, HandleImportCompletion::class);
        Event::listen(EmployeeSalaryUpdated::class, LogSalaryChange::class);
    }
}
