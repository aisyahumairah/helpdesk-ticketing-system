<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;

use Laravel\Socialite\Facades\Socialite;
use Exception;

class AuthController extends Controller
{
    // Show registration form
    public function showRegister()
    {
        return view('auth.register');
    }

    // Handle registration
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Auto-assign the default USER role
        $user->assignRole('user');

        Auth::login($user);
        return redirect()->route('dashboard');
    }

    // Show login form
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // ── Rate limiting / lockout ─────────────────────────────────────────
        $maxAttempts    = (int) (\App\Models\SystemSetting::where('key', 'login_limit')->value('value') ?? 5);
        $lockoutMinutes = (int) (\App\Models\SystemSetting::where('key', 'lockout_duration')->value('value') ?? 15);
        $decaySeconds   = $lockoutMinutes * 60;

        $throttleKey = 'login:' . strtolower($request->input('email')) . '|' . $request->ip();

        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($throttleKey, $maxAttempts)) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($throttleKey);
            $minutes = (int) ceil($seconds / 60);
            return back()->withErrors([
                'email' => "Too many login attempts. Please try again in {$minutes} minute(s).",
            ])->withInput($request->only('email'));
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Successful login — clear the rate-limit counter
            \Illuminate\Support\Facades\RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        // Failed — increment the rate limiter
        \Illuminate\Support\Facades\RateLimiter::hit($throttleKey, $decaySeconds);

        $attemptsUsed  = \Illuminate\Support\Facades\RateLimiter::attempts($throttleKey);
        $attemptsLeft  = max(0, $maxAttempts - $attemptsUsed);

        return back()->withErrors([
            'email' => $attemptsLeft > 0
                ? "The provided credentials do not match our records. {$attemptsLeft} attempt(s) remaining before lockout."
                : "Too many failed attempts. Your account is locked for {$lockoutMinutes} minute(s).",
        ])->withInput($request->only('email'));
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // --- Google Social Login ---

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if (!$user) {
                $user = User::create([
                    'name'                    => $googleUser->name,
                    'email'                   => $googleUser->email,
                    'google_id'               => $googleUser->id,
                    'password'                => Hash::make('abc123'),
                    'require_password_change' => true,
                    'email_verified_at'       => now(),
                ]);

                // Auto-assign the default USER role for new SSO accounts
                $user->assignRole('user');
            } else {
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->id]);
                }
            }

            Auth::login($user);
            return redirect()->route('dashboard');

        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Google login failed. Please try again.');
        }
    }

    // --- Profile Management ---

    // Show profile form
    public function showProfile()
    {
        return view('auth.profile', ['user' => Auth::user()]);
    }

    // Update profile
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($validated);
        return back()->with('success', 'Profile updated!');
    }

    // Show change password form
    public function showChangePassword()
    {
        return view('auth.change-password');
    }

    // Update password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:12',
                'regex:/[a-z]/',      // at least one lowercase letter
                'regex:/[A-Z]/',      // at least one uppercase letter
                'regex:/[0-9]/',      // at least one digit
                'regex:/[@$!%*#?&.]/', // at least one special character
                'confirmed'
            ],
        ], [
            'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.max' => 'The password may not be greater than 12 characters.',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
            'require_password_change' => false,
        ]);

        return back()->with('success', 'Password changed successfully!');
    }

    // --- Password Reset (Existing) ---

    // Show password reset request form
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    // Send password reset link
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink($request->only('email'));
        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    // Show password reset form
    public function showResetPassword($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    // Handle password reset
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'require_password_change' => true, // Enforce change on next login
                ])->save();
            }
        );
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
    public function markNotificationsAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    }
}
