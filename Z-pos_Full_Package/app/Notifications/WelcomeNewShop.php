<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNewShop extends Notification
{
    use Queueable;

    public $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Welcome to Z-POS!')
                    ->greeting('Hello ' . $this->user->first_name . ',')
                    ->line('Welcome to Z-POS! We are excited to have you on board.')
                    ->line('Your registration was successful. You can now start managing your sales and inventory with ease.')
                    ->action('Go to Dashboard', url('/home'))
                    ->line('If you have any questions or need support, feel free to contact us at info@z-pos.co.tz or via WhatsApp at +255 683 628 142.')
                    ->line('Thank you for choosing Z-POS!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
