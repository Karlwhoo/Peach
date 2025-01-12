<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Cache;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email'
            ]);

            // Check if user exists
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'No account found with this email address.'
                ], 404);
            }

            // Generate OTP
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Store OTP in cache
            $cacheKey = 'password_reset_' . $request->email;
            Cache::put($cacheKey, [
                'otp' => $otp,
                'email' => $request->email
            ], now()->addMinutes(10));

            // Send OTP email
            try {
                Mail::to($request->email)->send(new OtpMail($user->name, $otp));
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                \Log::error('Failed to send password reset OTP: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send OTP email. Please try again.'
                ], 500);
            }
        } catch (\Exception $e) {
            \Log::error('Error in password reset sendOtp: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        try {
            $request->validate([
                'otp' => 'required|string|size:6',
                'email' => 'required|email'
            ]);

            // Log the received data for debugging
            \Log::info('Verifying OTP:', [
                'email' => $request->email,
                'otp' => $request->otp
            ]);

            $cacheKey = 'password_reset_' . $request->email;
            $cachedData = Cache::get($cacheKey);

            \Log::info('Cached data:', ['data' => $cachedData]);

            if (!$cachedData) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP has expired. Please request a new one.'
                ], 400);
            }

            if ($cachedData['otp'] !== $request->otp) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP. Please try again.'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'OTP verified successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in verifyOtp: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error verifying OTP: ' . $e->getMessage()
            ], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        try {
            $user->password = Hash::make($request->password);
            $user->save();

            // Clear the reset cache
            Cache::forget('password_reset_' . $request->email);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset password'
            ], 500);
        }
    }
}
