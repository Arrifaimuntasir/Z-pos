<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class DailyNoSalesNotification extends Notification
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
        $hour = \Carbon\Carbon::now()->format('H');
        
        if ($hour >= 17) {
            return (new MailMessage)
                        ->subject('Taarifa ya Mwisho wa Siku - Z-POS')
                        ->greeting('Habari ' . $notifiable->first_name . ',')
                        ->line('Tumeona kuwa hujarekodi mauzo yoyote leo kwenye mfumo wako.')
                        ->line('Kama ulifanya mauzo leo, tafadhali hakikisha unayarekodi ili kuweka kumbukumbu sawa za hesabu zako na kuona faida yako.')
                        ->action('Ingia Kurekodi Mauzo', url('/sales/create'))
                        ->line('Asante kwa kutumia Z-POS!');
        } else {
            return (new MailMessage)
                        ->subject('Kikumbusho cha Mauzo - Z-POS')
                        ->greeting('Habari ' . $notifiable->first_name . ',')
                        ->line('Tunakukumbusha kurekodi mauzo yako kwa usahihi pindi wateja wanaponunua.')
                        ->line('Hadi sasa hivi hatuoni mauzo yoyote yaliyorekodiwa leo. Hakikisha unarekodi kila uuzacho ili kuweka kumbukumbu sawa.')
                        ->action('Ingia Kurekodi Mauzo', url('/sales/create'))
                        ->line('Asante kwa kutumia Z-POS!');
        }
    }

    /**
     * Get the web push representation of the notification.
     */
    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        $shopName = $notifiable->shop ? strtoupper($notifiable->shop->name) : 'Z-POS';
        $hour = \Carbon\Carbon::now()->format('H');
        
        if ($hour >= 17) {
            $title = $shopName . ' ' . __('End of Day');
            $body = __('No sales recorded today.');
        } else {
            $title = $shopName . ' - Kikumbusho';
            $body = 'Kumbuka kurekodi mauzo yako ya leo mapema.';
        }
        
        return (new WebPushMessage)
            ->title($title)
            ->body($body);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $hour = \Carbon\Carbon::now()->format('H');
        $message = $hour >= 17 ? __('No sales recorded today.') : 'Kumbuka kurekodi mauzo yako ya leo mapema.';

        return [
            'message' => $message,
            'action_url' => '/sales/create'
        ];
    }
}
