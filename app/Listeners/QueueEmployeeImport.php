<?php

namespace App\Listeners;

use App\Jobs\ProcessEmployeeImport;
use App\Events\EmployeeImportRequested;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class QueueEmployeeImport implements ShouldQueue
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
    public function handle(EmployeeImportRequested $event)
    {
        ProcessEmployeeImport::dispatch($event->import_job);
    }
}
