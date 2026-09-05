<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class SaleReturnNotification extends Notification
{
    use Queueable;

    protected $saleReturn;
    protected $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($saleReturn, $message)
    {
        $this->saleReturn = $saleReturn;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    /**
     * Get the web push representation of the notification.
     */
    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Taarifa ya Return (Bidhaa Zilizorudishwa)')
            ->icon('/images/icon-192.png')
            ->body($this->message)
            ->action('Angalia', url('/returns'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'sale_return_id' => $this->saleReturn->id,
            'reference_no' => $this->saleReturn->reference_no,
            'message' => $this->message,
            'action_url' => '/returns'
        ];
    }
}
