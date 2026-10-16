<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .header { background-color: #3b82f6; color: white; padding: 15px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { padding: 20px; }
        .footer { font-size: 12px; text-align: center; color: #888; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>{{ __('Subscription Expiring Soon') }}</h2>
        </div>
        <div class="content">
            <p>{{ __('Hello,') }}</p>
            <p>{{ __('This is a friendly reminder that the subscription for your shop,') }} <strong>{{ $shop->name }}</strong>{{ __(', is expiring in') }} <strong>{{ $daysLeft }} days</strong> (on {{ \Carbon\Carbon::parse($shop->valid_until)->format('M d, Y') }}).</p>
            <p>{{ __('To avoid any interruption to your service, please make a payment and upload your receipt in the system.') }}</p>
            <p>{{ __('Thank you for choosing Z-pos!') }}</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Zamar Store / Z-pos. All rights reserved.
        </div>
    </div>
</body>
</html>
