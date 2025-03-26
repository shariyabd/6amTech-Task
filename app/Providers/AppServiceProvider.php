<?php

namespace App\Providers;

use App\Listeners\LogSalaryChangeListener;
use App\Services\PerformanceMonitor;
use App\Events\EmployeeSalaryUpdatedEvent;
use Illuminate\Support\Facades\Event;
use App\Listeners\QueueEmployeeImportListener;
use App\Events\EmployeeImportCompletedEvent;
use App\Events\EmployeeImportRequestedEvent;
use Illuminate\Support\ServiceProvider;
use App\Listeners\HandleImportCompletionListener;

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
        Event::listen(EmployeeImportRequestedEvent::class, QueueEmployeeImportListener::class);
        Event::listen(EmployeeImportCompletedEvent::class, HandleImportCompletionListener::class);
        Event::listen(EmployeeSalaryUpdatedEvent::class, LogSalaryChangeListener::class);
    }
}
