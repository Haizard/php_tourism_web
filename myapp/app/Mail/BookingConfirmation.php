<?php

namespace App\Mail;

use App\Models\Booking;
use App\Settings\GeneralSettings;
use App\Settings\MailSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Booking $booking,
        public GeneralSettings $generalSettings,
        public MailSettings $mailSettings,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address(
                $this->mailSettings->fromAddress,
                $this->mailSettings->fromName,
            ),
            replyTo: $this->mailSettings->replyTo
                ? [new \Illuminate\Mail\Mailables\Address($this->mailSettings->replyTo)]
                : [],
            subject: 'Booking Confirmation – ' . $this->booking->tour->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.booking-confirmation',
        );
    }
}
