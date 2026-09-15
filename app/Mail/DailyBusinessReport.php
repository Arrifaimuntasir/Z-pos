<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailyBusinessReport extends Mailable
{
    use Queueable, SerializesModels;

    public $shop;
    public $owner;
    public $date;
    public $salesCount;
    public $totalRevenue;
    public $itemsSold;
    public $grossProfit;
    public $totalExpenses;
    public $topProducts;

    public function __construct($shop, $owner, $date, $salesCount, $totalRevenue, $itemsSold, $grossProfit, $totalExpenses, $topProducts)
    {
        $this->shop = $shop;
        $this->owner = $owner;
        $this->date = $date;
        $this->salesCount = $salesCount;
        $this->totalRevenue = $totalRevenue;
        $this->itemsSold = $itemsSold;
        $this->grossProfit = $grossProfit;
        $this->totalExpenses = $totalExpenses;
        $this->topProducts = $topProducts;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Daily Sales Report - ' . $this->shop->name . ' (' . $this->date->format('M d, Y') . ')',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.daily_report',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
