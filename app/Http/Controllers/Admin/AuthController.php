<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    private const GENERIC_ERROR = 'Invalid username or password.';

    public function login()
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:100',
            'password' => 'required|string|max:200',
        ]);

        $username = (string) $request->input('username');
        $ip = (string) $request->ip();

        // Only FAILED attempts count: per username+IP per minute, and per IP per hour.
        $userKey = 'admin-login:u:'.sha1(mb_strtolower($username).'|'.$ip);
        $ipKey = 'admin-login:ip:'.sha1($ip);
        $perMinute = max(1, (int) env('ADMIN_LOGIN_THROTTLE', 5));
        $perHour = max(1, (int) env('ADMIN_LOGIN_IP_HOURLY', 20));

        if (RateLimiter::tooManyAttempts($userKey, $perMinute) || RateLimiter::tooManyAttempts($ipKey, $perHour)) {
            $wait = max(RateLimiter::availableIn($userKey), RateLimiter::availableIn($ipKey), 1);

            return response()
                ->view('admin.login', ['errors' => (new \Illuminate\Support\ViewErrorBag)->put('default', new \Illuminate\Support\MessageBag(['username' => 'Too many attempts. Please try again later.']))], 429)
                ->header('Retry-After', (string) $wait);
        }

        $user = User::where('username', $username)->where('is_admin', true)->first();

        // Always run one bcrypt comparison so unknown usernames cost the same time as wrong passwords.
        $hash = $user ? $user->password : Cache::rememberForever('admin.dummy_hash', fn () => Hash::make(bin2hex(random_bytes(16))));
        $ok = Hash::check((string) $request->input('password'), $hash) && $user;

        if (! $ok) {
            RateLimiter::hit($userKey, 60);
            RateLimiter::hit($ipKey, 3600);

            return back()
                ->withErrors(['username' => self::GENERIC_ERROR])
                ->withInput(['username' => $username]);
        }

        RateLimiter::clear($userKey);
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // Fixed destination: no redirect()->intended() / user-supplied URLs, so no open redirect.
        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
