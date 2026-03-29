<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactInquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $senderName;
    public string $senderEmail;
    public string $inquirySubject;
    public string $messageBody;

    public function __construct(
        string $senderName,
        string $senderEmail,
        string $inquirySubject,
        string $messageBody,
    ) {
        $this->senderName     = $senderName;
        $this->senderEmail    = $senderEmail;
        $this->inquirySubject = $inquirySubject;
        $this->messageBody    = $messageBody;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [$this->senderEmail],
            subject: '[ASEANAPOL Inquiry] ' . $this->inquirySubject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.contact-inquiry',
        );
    }
}
