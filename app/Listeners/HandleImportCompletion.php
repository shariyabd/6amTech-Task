<?php

namespace App\Listeners;

use App\Models\ImportJob;
use App\Models\ImportStatistic;
use App\Mail\ImportSummaryReport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Events\EmployeeImportCompleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleImportCompletion implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

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
    public function handle(EmployeeImportCompleted $event): void
    {
        $importJob = $event->import_job;

        Log::info("Import completed successfully", [
            'import_job_id' => $importJob->id,
            'processed' => $importJob->processed_records,
            'failed' => $importJob->failed_records
        ]);

        // 1. Generate import summary statistics
        $stas = $this->generateImportStats($importJob);
        $this->sendAdminReport($importJob, $stas);
    }

    /**
     * Generate detailed import statistics.
     */
    private function generateImportStats(ImportJob $importJob)
    {
        // Basic stats
        $stats = [
            'import_id' => $importJob->id,
            'user_id' => $importJob->user_id,
            'total_records' => $importJob->total_records,
            'processed_records' => $importJob->processed_records,
            'failed_records' => $importJob->failed_records,
            'success_rate' => $importJob->total_records > 0
                ? round((($importJob->processed_records - $importJob->failed_records) / $importJob->total_records) * 100, 2)
                : 0,
            'duration' => $importJob->updated_at->diffInSeconds($importJob->created_at),
            'records_per_second' => $importJob->updated_at->diffInSeconds($importJob->created_at) > 0
                ? round($importJob->processed_records / $importJob->updated_at->diffInSeconds($importJob->created_at), 2)
                : 0,
            'completed_at' => $importJob->updated_at->toDateTimeString(),
        ];
        ImportStatistic::create($stats);
        return $stats;
    }


    private function sendAdminReport(ImportJob $importJob, array $stats): void
    {
        try {
            // Only send admin reports for large imports or when there are significant errors
            if ($importJob->total_records > 1000 || $importJob->failed_records > 10) {
                Mail::to('shariya@gmail.com')
                    ->queue(new ImportSummaryReport($importJob, $stats));
            }
        } catch (\Exception $e) {
            Log::error("Failed to send admin report email: " . $e->getMessage());
        }
    }
}
