<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .header { background-color: #ef4444; color: white; padding: 15px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { padding: 20px; }
        .btn { display: inline-block; background-color: #3b82f6; color: #ffffff; text-decoration: none; padding: 10px 20px; border-radius: 6px; margin-top: 10px; }
        .footer { font-size: 12px; text-align: center; color: #888; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>{{ __('Taarifa ya Mwisho wa Siku') }}</h2>
        </div>
        <div class="content">
            <p>Habari {{ $owner->first_name }},</p>
            <p>Tumeona kuwa duka lako, <strong>{{ $shop->name }}</strong>, halijarekodi mauzo yoyote leo kwenye mfumo wa Z-POS.</p>
            <p>Kama ulifanya mauzo leo, tafadhali hakikisha unayarekodi ili kuweka kumbukumbu sawa za hesabu zako na kuona faida yako.</p>
            <p><a class="btn" href="{{ url('/sales/create') }}">Ingia Kurekodi Mauzo</a></p>
            <p>Asante kwa kutumia Z-POS!</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Z-pos. All rights reserved.
        </div>
    </div>
</body>
</html>
