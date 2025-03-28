<?php

namespace App\Listeners;

use App\Models\ImportJob;
use App\Models\ImportStatistic;
use App\Mail\ImportSummaryReport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\EmployeeImportCompletedEvent;

class HandleImportCompletionListener implements ShouldQueue
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
    public function handle(EmployeeImportCompletedEvent $event): void
    {
        $import_job = $event->import_job;

        //Generate import summary statistics
        $stas = $this->generateImportStats($import_job);
        $this->sendAdminReport($import_job, $stas);
    }

    /**
     *  detailed import statistics.
     */
    private function generateImportStats(ImportJob $import_job)
    {
        try {
            //  duration in seconds
            $duration = $import_job->updated_at->diffInSeconds($import_job->created_at);
            //  success rate
            $success_rate = $import_job->total_records > 0 ? round((($import_job->processed_records - $import_job->failed_records) / $import_job->total_records) * 100, 2) : 0;

            //  records processed per second
            $records_per_second = $duration > 0 ? round($import_job->processed_records / $duration, 2) : 0;


            $import_statistic                        = new ImportStatistic();
            $import_statistic->import_id             = $import_job->id;
            $import_statistic->user_id               = $import_job->user_id;
            $import_statistic->total_records         = $import_job->total_records;
            $import_statistic->processed_records     = $import_job->processed_records;
            $import_statistic->failed_records        = $import_job->failed_records;
            $import_statistic->success_rate          = $success_rate;
            $import_statistic->duration              = $duration;
            $import_statistic->records_per_second    = $records_per_second;
            $import_statistic->completed_at          = $import_job->updated_at->toDateTimeString();
            $import_statistic->save();

            return $import_statistic;
        } catch (\Exception $e) {
            Log::error("Failed to generate import statistics: " . $e->getMessage());
        }
    }



    private function sendAdminReport(ImportJob $importJob, $stats)
    {

        try {
            //  send admin reports for large imports or when there are significant errors
            if ($importJob->total_records > 100 || $importJob->failed_records > 10) {
                $adminEmail = $importJob->user->email ?? 'shariya873@gmail.com';
                Mail::to($adminEmail)->queue(new ImportSummaryReport($importJob, $stats));
            }
        } catch (\Exception $e) {
            Log::error("Failed to send admin report email: " . $e->getMessage());
        }
    }
}
