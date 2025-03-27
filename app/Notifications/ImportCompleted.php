<?php

namespace App\Notifications;

use App\Models\ImportJob;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ImportCompleted extends Notification
{
    use Queueable;

    public $importJob;

    public function __construct(ImportJob $importJob)
    {
        $this->importJob = $importJob;
    }

    public function via($notifiable)
    {
        return ['mail','database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Employee Import Completed')
            ->line('Your employee data import has been completed.')
            ->line("Total records: {$this->importJob->total_records}")
            ->line("Successfully processed: " . ($this->importJob->processed_records - $this->importJob->failed_records))
            ->line("Failed records: {$this->importJob->failed_records}")
            ->action('View Details', url(route('import.status', $this->importJob->id)));
    }

    public function toArray($notifiable)
    {
        return [
            'import_job_id' => $this->importJob->id,
            'status' => 'completed',
            'message' => 'Employee import completed successfully',
            'total_records' => $this->importJob->total_records,
            'processed_records' => $this->importJob->processed_records,
            'failed_records' => $this->importJob->failed_records
        ];
    }
}
