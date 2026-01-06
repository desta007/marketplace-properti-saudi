<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('web.auth.login');
    }

    /**
     * Send OTP to email
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->email;
        $code = OtpCode::generateCode();

        // Delete old unused OTPs
        OtpCode::where('email', $email)
            ->whereNull('verified_at')
            ->delete();

        // Create new OTP
        OtpCode::create([
            'email' => $email,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Send email
        try {
            Mail::raw("Your SaudiProp verification code is: {$code}", function ($message) use ($email) {
                $message->to($email)->subject('SaudiProp - Verification Code');
            });
        } catch (\Exception $e) {
            \Log::warning('Failed to send OTP email: ' . $e->getMessage());
        }

        return back()->with([
            'otp_sent' => true,
            'email' => $email,
            'message' => 'Verification code sent to your email',
        ]);
    }

    /**
     * Verify OTP and login
     */
    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        $otp = OtpCode::where('email', $request->email)
            ->where('code', $request->code)
            ->whereNull('verified_at')
            ->first();

        if (!$otp || $otp->isExpired()) {
            return back()->withErrors(['code' => 'Invalid or expired verification code']);
        }

        // Mark OTP as verified
        $otp->markAsVerified();

        // Find or create user
        $user = User::firstOrCreate(
            ['email' => $request->email],
            [
                'name' => explode('@', $request->email)[0],
                'email_verified_at' => Carbon::now(),
                'language' => app()->getLocale(),
            ]
        );

        // Login user
        Auth::login($user, true);

        return redirect()->intended('/')->with('success', 'Welcome back!');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
