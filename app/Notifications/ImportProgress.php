<?php

namespace App\Notifications;

use App\Models\ImportJob;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;;

class ImportProgress extends Notification
{
    use Queueable;


    public $importJob;
    public $progress;

    public function __construct(ImportJob $importJob, $progress)
    {
        $this->importJob = $importJob;
        $this->progress = $progress;
    }

    public function via($notifiable)
    {
        return ['database'];
    }



    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Import Progress')
            ->line('Your import process is in progress.')
            ->action('View Import', url('/imports'))
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'import_job_id' => $this->importJob->id,
            'progress'      => $this->progress,
            'message'       => "Import in progress: {$this->progress}% complete"
        ];
    }
}
