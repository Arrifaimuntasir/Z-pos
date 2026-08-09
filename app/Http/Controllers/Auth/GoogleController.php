<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle(Request $request)
    {
        // Store shop_name and package in session if provided (from Register page)
        if ($request->has('shop_name')) {
            $request->session()->put('google_shop_name', $request->input('shop_name'));
        }
        if ($request->has('package')) {
            $request->session()->put('google_package', $request->input('package'));
        }

        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['email' => 'Failed to authenticate with Google.']);
        }

        // Check if user already exists
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // Update Google ID if not set
            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->getId()]);
            }
            
            Auth::login($user);
            return redirect()->route('home');
        }

        // User does not exist, so we are registering them.
        // Check if they provided a shop_name before going to Google
        $shopName = $request->session()->pull('google_shop_name');
        $package = $request->session()->pull('google_package', 'starter');

        if (!$shopName) {
            // If somehow they bypassed the shop name, fallback to a default
            $firstName = explode(' ', $googleUser->getName())[0] ?? 'My';
            $shopName = $firstName . "'s Shop";
        }

        // Split name into first and last name
        $nameParts = explode(' ', $googleUser->getName());
        $firstName = array_shift($nameParts);
        $lastName = count($nameParts) > 0 ? implode(' ', $nameParts) : '';

        // Create Shop
        $shop = Shop::create([
            'name' => $shopName,
            'package' => $package,
            'valid_until' => now()->addDays(7), // 7 days free trial
        ]);

        // Create User
        $newUser = User::create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $googleUser->getEmail(),
            'google_id' => $googleUser->getId(),
            'password' => null, // No password for Google users
            'shop_id' => $shop->id,
        ]);

        Auth::login($newUser);

        // This will redirect to home, but the SubscriptionMiddleware will catch it 
        // because valid_until is in the past, and redirect to payments.expired.
        return redirect()->route('home');
    }
}
