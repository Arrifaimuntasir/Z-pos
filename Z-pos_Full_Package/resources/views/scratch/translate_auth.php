<?php
$files = [
    'e:/Z-pos/resources/views/auth/login.blade.php',
    'e:/Z-pos/resources/views/auth/register.blade.php',
    'e:/Z-pos/resources/views/auth/passwords/email.blade.php'
];

$replacements = [
    '> Back to Home<' => '> {{ __(\'Back to Home\') }}<',
    '>Welcome Back<' => '>{{ __(\'Welcome Back\') }}<',
    '>Log in to your account to continue<' => '>{{ __(\'Log in to your account to continue\') }}<',
    '>Email Address<' => '>{{ __(\'Email Address\') }}<',
    'placeholder="Enter your email"' => 'placeholder="{{ __(\'Enter your email\') }}"',
    '>Password<' => '>{{ __(\'Password\') }}<',
    '>Forgot Password?<' => '>{{ __(\'Forgot Password?\') }}<',
    'placeholder="Enter your password"' => 'placeholder="{{ __(\'Enter your password\') }}"',
    '> Remember Me <' => '> {{ __(\'Remember Me\') }} <',
    '>\n                        Log In\n                    <' => '>{{ __(\'Log In\') }}<',
    '>Don\'t have an account? <' => '>{{ __(\'Don\\\'t have an account?\') }} <',
    '>Sign Up<' => '>{{ __(\'Sign Up\') }}<',
    '>Create an Account<' => '>{{ __(\'Create an Account\') }}<',
    '>Join Z-pos and start managing your retail business<' => '>{{ __(\'Join Z-pos and start managing your retail business\') }}<',
    '>Full Name<' => '>{{ __(\'Full Name\') }}<',
    'placeholder="Enter your full name"' => 'placeholder="{{ __(\'Enter your full name\') }}"',
    '>Phone Number<' => '>{{ __(\'Phone Number\') }}<',
    'placeholder="e.g. 0712345678"' => 'placeholder="{{ __(\'e.g. 0712345678\') }}"',
    '>Shop Name<' => '>{{ __(\'Shop Name\') }}<',
    'placeholder="e.g. Zamar Store"' => 'placeholder="{{ __(\'e.g. Zamar Store\') }}"',
    '>Confirm Password<' => '>{{ __(\'Confirm Password\') }}<',
    'placeholder="Confirm your password"' => 'placeholder="{{ __(\'Confirm your password\') }}"',
    '>\n                        Create Account\n                    <' => '>{{ __(\'Create Account\') }}<',
    '>Already have an account? <' => '>{{ __(\'Already have an account?\') }} <',
    '>Reset Password<' => '>{{ __(\'Reset Password\') }}<',
    '>Enter your email to receive a password reset link<' => '>{{ __(\'Enter your email to receive a password reset link\') }}<',
    '>\n                        Send Password Reset Link\n                    <' => '>{{ __(\'Send Password Reset Link\') }}<'
];

foreach ($files as $path) {
    if (!file_exists($path)) continue;
    $c = file_get_contents($path);
    foreach ($replacements as $search => $replace) {
        $c = str_replace($search, $replace, $c);
    }
    // Specific fixes
    $c = str_replace('>Remember Me', '>{{ __(\'Remember Me\') }}', $c);
    $c = preg_replace('/>\s*Log In\s*</s', '>{{ __(\'Log In\') }}<', $c);
    $c = preg_replace('/>\s*Create Account\s*</s', '>{{ __(\'Create Account\') }}<', $c);
    $c = preg_replace('/>\s*Send Password Reset Link\s*</s', '>{{ __(\'Send Password Reset Link\') }}<', $c);
    
    file_put_contents($path, $c);
    echo "Updated $path\n";
}
