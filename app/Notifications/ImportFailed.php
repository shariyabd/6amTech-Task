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

    public $importJob;

    public function __construct(ImportJob $importJob)
    {
        $this->importJob = $importJob;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Employee Import Failed')
            ->line('Unfortunately, your employee data import has failed.')
            ->line("Error: {$this->importJob->error_message}")
            ->line("Total records: {$this->importJob->total_records}")
            ->line("Processed before failure: {$this->importJob->processed_records}")
            ->action('View Details', route('import.status', $this->importJob->id));
    }

    public function toArray($notifiable)
    {
        return [
            'import_job_id' => $this->importJob->id,
            'status'        => 'failed',
            'message'       => 'Employee import failed',
            'error'         => $this->importJob->error_message
        ];
    }
}
