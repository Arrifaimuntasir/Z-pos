<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewShopRegistration extends Notification
{
    use Queueable;

    public $shop;
    public $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($shop, $user)
    {
        $this->shop = $shop;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('New Shop Registered: ' . $this->shop->name)
                    ->greeting('Hello Super Admin,')
                    ->line('A new shop has just registered on Z-POS.')
                    ->line('Shop Name: ' . $this->shop->name)
                    ->line('Owner: ' . $this->user->first_name . ' ' . $this->user->last_name)
                    ->line('Phone: ' . ($this->user->phone ?? 'N/A'))
                    ->line('Email: ' . $this->user->email)
                    ->line('Business Type: ' . ($this->shop->business_type ?? 'Not Specified'))
                    ->line('Package: ' . $this->shop->package)
                    ->action('View Shops', url('/superadmin/shops'))
                    ->line('Thank you for using Z-POS.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'shop_id' => $this->shop->id,
            'shop_name' => $this->shop->name,
            'business_type' => $this->shop->business_type ?? 'Not Specified',
            'message' => 'New shop registered: ' . $this->shop->name . ' (' . ($this->shop->business_type ?? 'Not Specified') . ')',
        ];
    }
}
