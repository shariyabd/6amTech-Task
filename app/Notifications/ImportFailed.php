<?php

namespace App\Notifications;

use App\Models\ImportJob;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ImportFailed extends Notification
{
    use Queueable;

    public $import_job;

    public function __construct(ImportJob $importJob)
    {
        $this->import_job = $importJob;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Employee Import Failed')
            ->line('Unfortunately, your employee data import has failed.')
            ->line("Error: {$this->import_job->error_message}")
            ->line("Total records: {$this->import_job->total_records}")
            ->line("Processed before failure: {$this->import_job->processed_records}")
            ->action('View Details', url(route('import.status', $this->import_job->id)));
    }

    public function toArray($notifiable)
    {
        return [
            'import_job_id' => $this->import_job->id,
            'status'        => 'failed',
            'message'       => 'Employee import failed',
            'error'         => $this->import_job->error_message
        ];
    }
}
