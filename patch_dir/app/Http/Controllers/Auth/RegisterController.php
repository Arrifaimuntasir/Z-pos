<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm(\Illuminate\Http\Request $request)
    {
        $package = $request->query('package', 'starter');
        return response()->view('auth.register', compact('package'))
                         ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                         ->header('Pragma', 'no-cache')
                         ->header('Expires', '0')
                         ->header('X-LiteSpeed-Cache-Control', 'no-cache');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'shop_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20', 'unique:users', 'regex:/^\+[1-9]\d{7,14}$/'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'package' => ['nullable', 'string', 'in:starter,professional,enterprise'],
            'business_type' => ['required', 'string', 'in:Retail / General,Electronics / IT,Pharmacy / Health,Supermarket / Grocery,Restaurant / Food,Hardware / Construction,Clothing / Boutique'],
        ], [
            'business_type.in' => __('Please select another business. Services / Consulting is coming soon.'),
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @return User
     */
    protected function create(array $data)
    {
        $shop = \App\Models\Shop::create([
            'name' => $data['shop_name'],
            'business_type' => $data['business_type'],
            'package' => $data['package'] ?? 'starter',
            'valid_until' => now()->addDays(7), // 7 days free trial
        ]);

        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'shop_id' => $shop->id,
        ]);

        $branch = \App\Models\Branch::create([
            'shop_id' => $shop->id,
            'name' => 'Main Branch',
            'is_active' => true,
        ]);

        $user->update(['branch_id' => $branch->id]);

        $user->assignRole('Administrator');

        // Send welcome email to the new user
        try {
            $user->notify(new \App\Notifications\WelcomeNewShop($user));
            
            // Notify super admins
            $superAdmins = User::role('Super Admin')->get();
            foreach ($superAdmins as $admin) {
                $admin->notify(new \App\Notifications\NewShopRegistration($shop, $user));
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Registration notification failed: ' . $e->getMessage());
        }

        return $user;
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(\Illuminate\Http\Request $request, $user)
    {
        \Illuminate\Support\Facades\Cookie::queue(\Illuminate\Support\Facades\Cookie::forever('pwa_onboarding_seen', 'true'));
    }

    /**
     * Handle a registration request for the application.
     * Overridden to prevent auto-login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(\Illuminate\Http\Request $request)
    {
        $this->validator($request->all())->validate();

        event(new \Illuminate\Auth\Events\Registered($user = $this->create($request->all())));

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
                    ? new \Illuminate\Http\JsonResponse([], 201)
                    : redirect()->route('registration.success');
    }

    /**
     * Show the registration success page.
     *
     * @return \Illuminate\View\View
     */
    public function success()
    {
        return view('auth.registration-success');
    }
}
