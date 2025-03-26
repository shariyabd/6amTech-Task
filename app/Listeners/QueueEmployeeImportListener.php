<?php

namespace App\Listeners;

use App\Jobs\ProcessEmployeeImport;
use App\Events\EmployeeImportRequestedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class QueueEmployeeImportListener implements ShouldQueue
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
    public function handle(EmployeeImportRequestedEvent $event)
    {
        ProcessEmployeeImport::dispatch($event->import_job);
    }
}
