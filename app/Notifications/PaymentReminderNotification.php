<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class PaymentReminderNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
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
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Taarifa ya Malipo - Z-POS')
                    ->greeting('Habari ' . $notifiable->first_name . ',')
                    ->line('Muda wako wa kutumia mfumo wa Z-POS umekwisha au unakaribia kuisha.')
                    ->line('Tafadhali fanya malipo mapema ili uendelee kufurahia huduma bila usumbufu wa mfumo kufungwa.')
                    ->action('Lipia Sasa', url('/billing'))
                    ->line('Asante kwa kuendelea kutumia Z-POS!');
    }

    /**
     * Get the web push representation of the notification.
     */
    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Z-POS: Taarifa ya Malipo')
            ->icon('/images/icon-192.png')
            ->body('Muda wako wa kutumia mfumo umekwisha au unakaribia kuisha. Tafadhali fanya malipo ili kuendelea.')
            ->action('Lipia Sasa', url('/billing'));
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'message' => 'Muda wako wa kutumia mfumo unakaribia kuisha. Tafadhali fanya malipo.',
            'action_url' => '/billing'
        ];
    }
}
