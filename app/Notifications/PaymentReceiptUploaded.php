<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class PaymentReceiptUploaded extends Notification
{
    use Queueable;

    public $shop;

    /**
     * Create a new notification instance.
     */
    public function __construct($shop)
    {
        $this->shop = $shop;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database', WebPushChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Payment Receipt Uploaded: ' . $this->shop->name)
                    ->greeting('Hello Super Admin,')
                    ->line('A shop has uploaded a new payment receipt.')
                    ->line('Shop Name: ' . $this->shop->name)
                    ->line('Package: ' . $this->shop->package)
                    ->action('View Payments', url('/superadmin/payments'))
                    ->line('Please review and approve the payment to activate their subscription.');
    }

    /**
     * Get the web push representation of the notification.
     */
    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('New Payment Receipt')
            ->icon(url('/images/icon-192.png'))
            ->body('Shop "' . $this->shop->name . '" has uploaded a payment receipt.')
            ->action('View Payments', 'view_payments')
            ->data(['url' => url('/superadmin/payments')]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Payment Receipt Uploaded',
            'message' => 'Shop "' . $this->shop->name . '" has uploaded a payment receipt for their ' . $this->shop->package . ' package.',
            'url' => '/superadmin/payments',
        ];
    }
}
