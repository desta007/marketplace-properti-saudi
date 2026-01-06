<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\OtpCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Send OTP to email
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->email;
        
        // Generate 6-digit OTP
        $code = OtpCode::generateCode();
        
        // Delete any existing unused OTPs for this email
        OtpCode::where('email', $email)
            ->whereNull('verified_at')
            ->delete();
        
        // Create new OTP
        OtpCode::create([
            'email' => $email,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);
        
        // Send email (in production, use proper mail template)
        try {
            Mail::raw("Your SaudiProp verification code is: {$code}", function ($message) use ($email) {
                $message->to($email)
                    ->subject('SaudiProp - Verification Code');
            });
        } catch (\Exception $e) {
            // Log error but don't fail - in dev mode mail might not be configured
            \Log::warning('Failed to send OTP email: ' . $e->getMessage());
        }
        
        return response()->json([
            'message' => 'OTP sent successfully',
            'email' => $email,
            'expires_in' => 600, // 10 minutes in seconds
        ]);
    }

    /**
     * Verify OTP and login/register user
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        $otp = OtpCode::where('email', $request->email)
            ->where('code', $request->code)
            ->whereNull('verified_at')
            ->first();

        if (!$otp) {
            throw ValidationException::withMessages([
                'code' => ['Invalid verification code.'],
            ]);
        }

        if ($otp->isExpired()) {
            throw ValidationException::withMessages([
                'code' => ['Verification code has expired.'],
            ]);
        }

        // Mark OTP as verified
        $otp->markAsVerified();

        // Find or create user
        $user = User::firstOrCreate(
            ['email' => $request->email],
            [
                'name' => explode('@', $request->email)[0], // Use email prefix as default name
                'email_verified_at' => Carbon::now(),
                'language' => 'ar',
            ]
        );

        // Update email verification if not already verified
        if (!$user->email_verified_at) {
            $user->update(['email_verified_at' => Carbon::now()]);
        }

        // Create token
        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => new UserResource($user),
            'token' => $token,
        ]);
    }

    /**
     * Complete registration (update profile after OTP login)
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'language' => 'nullable|in:ar,en',
        ]);

        $user = $request->user();
        $user->update($request->only(['name', 'phone', 'language']));

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => new UserResource($user),
        ]);
    }

    /**
     * Get current user
     */
    public function user(Request $request)
    {
        return new UserResource($request->user());
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        // Revoke current token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }
}
