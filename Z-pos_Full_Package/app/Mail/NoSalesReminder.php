<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NoSalesReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $shop;
    public $owner;

    public function __construct($shop, $owner)
    {
        $this->shop = $shop;
        $this->owner = $owner;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Taarifa ya Mwisho wa Siku - Hujarekodi Mauzo Leo',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.no_sales_reminder',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
