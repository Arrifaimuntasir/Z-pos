<?php
$path='e:/Z-pos/lang/sw.json';
$sw=json_decode(file_get_contents($path), true);
$new = [
    'Back to Home'=>'Rudi Mwanzo',
    'Welcome Back'=>'Karibu Tena',
    'Log in to your account to continue'=>'Ingia kwenye akaunti yako kuendelea',
    'Email Address'=>'Barua Pepe',
    'Enter your email'=>'Ingiza barua pepe yako',
    'Password'=>'Nenosiri',
    'Forgot Password?'=>'Umesahau Nenosiri?',
    'Enter your password'=>'Ingiza nenosiri lako',
    'Remember Me'=>'Nikumbuke',
    'Log In'=>'Ingia',
    'Don\'t have an account?'=>'Huna akaunti?',
    'Sign Up'=>'Jisajili',
    'Create an Account'=>'Tengeneza Akaunti',
    'Join Z-pos and start managing your retail business'=>'Jiunge na Z-pos na uanze kusimamia biashara yako',
    'Full Name'=>'Jina Kamili',
    'Enter your full name'=>'Ingiza jina lako kamili',
    'Phone Number'=>'Namba ya Simu',
    'e.g. 0712345678'=>'Mfano 0712345678',
    'Shop Name'=>'Jina la Duka',
    'e.g. Zamar Store'=>'Mfano Duka Langu',
    'Confirm Password'=>'Thibitisha Nenosiri',
    'Confirm your password'=>'Thibitisha nenosiri lako',
    'Create Account'=>'Tengeneza Akaunti',
    'Already have an account?'=>'Tayari una akaunti?',
    'Reset Password'=>'Badili Nenosiri',
    'Enter your email to receive a password reset link'=>'Ingiza barua pepe yako kupokea link ya kubadili nenosiri',
    'Send Password Reset Link'=>'Tuma Link ya Kubadili Nenosiri'
];
$sw = array_merge($sw, $new);
file_put_contents($path, json_encode($sw, JSON_PRETTY_PRINT));
echo "sw.json updated.";
