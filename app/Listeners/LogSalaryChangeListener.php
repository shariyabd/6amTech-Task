<?php

namespace App\Listeners;

use App\Models\SalaryChangeLog;
use Illuminate\Support\Facades\Log;
use App\Events\EmployeeSalaryUpdatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogSalaryChangeListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(EmployeeSalaryUpdatedEvent $event)
    {
        // salary change history
        SalaryChangeLog::create([
            'employee_id' => $event->employee->id,
            'old_salary' => $event->oldSalary,
            'new_salary' => $event->newSalary,
            'changed_by' => 'system_import',
            'change_reason' => 'Data import'
        ]);
    }
}
