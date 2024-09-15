<?php

namespace App\Mail;

use App\Models\Quotation;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuotationSent extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        protected Quotation $quotation,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Quotation',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.quotation',
            with: [
                'reference_number' => $this->quotation->reference_number,
                'available_items' => $this->quotation->items()->where('status', 'available')->get(),
                'unavailable_items' => $this->quotation->items()->whereNot('status', 'available')->get(),
                'date_sent' => (new Carbon('now'))
                    ->tz('Asia/Manila')
                    ->format('M d, Y H:i:s A'),
            ],
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
