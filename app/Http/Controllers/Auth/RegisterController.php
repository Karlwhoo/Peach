<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function sendOtp(Request $request)
    {
        try {
            // Add debugging
            \Log::info('Checking email: ' . $request->email);
            
            // Check if email exists before other validations
            $existingUser = User::where('email', $request->email)->first();
            \Log::info('Existing user check result: ', ['exists' => !is_null($existingUser)]);
            
            if ($existingUser) {
                \Log::info('Email already exists: ' . $request->email);
                return response()->json([
                    'success' => false,
                    'message' => 'This email address is already registered. Please use a different email or login to your existing account.'
                ], 422);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:8|confirmed',
            ]);

            // Generate OTP
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Store OTP and registration data in cache
            $cacheKey = 'registration_' . $request->email;
            Cache::put($cacheKey, [
                'otp' => $otp,
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ], now()->addMinutes(10));

            // Send OTP email
            try {
                Mail::to($request->email)->send(new OtpMail($request->name, $otp));
                \Log::info('OTP email sent successfully to: ' . $request->email);
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                \Log::error('Failed to send OTP email: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send OTP email: ' . $e->getMessage()
                ], 500);
            }
        } catch (\Exception $e) {
            \Log::error('Error in sendOtp: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
            'email' => 'required|email'
        ]);

        $cacheKey = 'registration_' . $request->email;
        $cachedData = Cache::get($cacheKey);

        if (!$cachedData || $cachedData['otp'] !== $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP'
            ], 400);
        }

        // Create user
        try {
            User::create([
                'name' => $cachedData['name'],
                'email' => $cachedData['email'],
                'password' => Hash::make($cachedData['password']),
            ]);

            // Clear the cache
            Cache::forget($cacheKey);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user'
            ], 500);
        }
    }
}
