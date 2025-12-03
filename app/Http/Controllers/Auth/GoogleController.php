<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirect()
    {
        return Socialite::driver('google')
            ->scopes(['email', 'profile'])
            ->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Validate that this is an AES email
            if (!$this->isValidAESEmail($googleUser->email)) {
                return redirect('/')->with('error', 'Please use your AES school email account to log in.');
            }
            
            // Find or create user
            $user = User::where('email', $googleUser->email)->first();
            
            if ($user) {
                // Update existing user with Google info
                $user->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'last_login_at' => now(),
                ]);
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'password' => Hash::make(Str::random(10)), // Random 10-digit code for OAuth users
                    'email_verified_at' => now(),
                    'division_id' => User::detectDivisionFromEmail($googleUser->email),
                    'last_login_at' => now(),
                    'is_active' => true,
                ]);
            }
            
            Auth::login($user, true);
            
            // Redirect based on user role
            if ($user->is_admin) {
                return redirect()->intended('/admin/dashboard')->with('success', 'Welcome to AES Admin Panel!');
            } else {
                return redirect()->intended('/dashboard')->with('success', 'Welcome to AES Professional Learning Days!');
            }
            
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'There was an error logging in with Google. Please try again.');
        }
    }

    /**
     * Validate if email belongs to AES
     */
    private function isValidAESEmail(string $email): bool
    {
        // Add your AES email domain validation here
        $validDomains = [
            'aes.ac.in',
            'staff.aes.ac.in',
            'teachers.aes.ac.in',
            // Add other valid AES email domains
        ];
        
        foreach ($validDomains as $domain) {
            if (str_ends_with($email, '@' . $domain)) {
                return true;
            }
        }
        
        // For development, allow any email
        if (config('app.env') === 'local') {
            return true;
        }
        
        return false;
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'You have been logged out successfully.');
    }
}
