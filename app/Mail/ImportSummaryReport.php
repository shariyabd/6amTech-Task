<?php

namespace App\Mail;

use App\Models\ImportJob;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImportSummaryReport extends Mailable
{
    use Queueable, SerializesModels;

    public $import_job;
    public $stats;

    /**
     * Create a new message instance.
     */
    public function __construct(ImportJob $import_job, array $stats)
    {
        $this->import_job = $import_job;
        $this->stats = $stats;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Employee Import Summary - Job #{$this->import_job->id}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.imports.summary',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
