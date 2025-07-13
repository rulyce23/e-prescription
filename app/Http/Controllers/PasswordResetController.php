<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password as PasswordRules;

class PasswordResetController extends Controller
{
    /**
     * Show the password reset request form
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link
     */
    public function sendResetLink(Request $request)
    {
        // Rate limiting untuk mencegah brute force
        $throttleKey = 'password-reset:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Terlalu banyak percobaan. Silakan coba lagi dalam {$seconds} detik."
            ]);
        }

        // Validasi input dengan sanitasi
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.max' => 'Email terlalu panjang',
        ]);

        if ($validator->fails()) {
            RateLimiter::hit($throttleKey);
            return back()->withErrors($validator)->withInput();
        }

        // Sanitasi email
        $email = filter_var($request->email, FILTER_SANITIZE_EMAIL);
        
        // Cek apakah email ada di database
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            // Jangan beri tahu bahwa email tidak ada (security)
            RateLimiter::hit($throttleKey);
            return back()->with('status', 'Jika email terdaftar, link reset password akan dikirim.');
        }

        // Generate token yang aman
        $token = Str::random(64);
        $expiresAt = now()->addMinutes(60);

        // Hapus token lama jika ada
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        // Simpan token baru dengan informasi keamanan
        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => Hash::make($token),
            'created_at' => now(),
            'expires_at' => $expiresAt,
            'used' => false,
            'request_ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // Kirim email reset password
        try {
            Mail::send('emails.reset-password', [
                'user' => $user,
                'token' => $token,
                'expiresAt' => $expiresAt
            ], function ($message) use ($user) {
                $message->to($user->email, $user->name)
                        ->subject('Reset Password - E-Prescription System')
                        ->priority(1);
            });

            RateLimiter::hit($throttleKey);
            return back()->with('status', 'Link reset password telah dikirim ke email Anda.');
            
        } catch (\Exception $e) {
            // Log error tapi jangan expose ke user
            Log::error('Password reset email failed: ' . $e->getMessage());
            
            RateLimiter::hit($throttleKey);
            return back()->withErrors(['email' => 'Gagal mengirim email. Silakan coba lagi.']);
        }
    }

    /**
     * Show the password reset form
     */
    public function showResetPassword(Request $request, $token)
    {
        $email = $request->query('email');
        
        if (!$email || !$token) {
            return redirect()->route('login')->withErrors(['email' => 'Link reset password tidak valid.']);
        }

        // Validasi token
        $resetToken = DB::table('password_reset_tokens')
                        ->where('email', $email)
                        ->where('used', false)
                        ->where('expires_at', '>', now())
                        ->first();

        if (!$resetToken || !Hash::check($token, $resetToken->token)) {
            return redirect()->route('login')->withErrors(['email' => 'Link reset password tidak valid atau sudah kadaluarsa.']);
        }

        return view('auth.reset-password', compact('email', 'token'));
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        // Rate limiting
        $throttleKey = 'password-reset-confirm:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'password' => "Terlalu banyak percobaan. Silakan coba lagi dalam {$seconds} detik."
            ]);
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'token' => 'required|string|min:64|max:255',
            'email' => 'required|email|max:255',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:255',
                'confirmed',
                PasswordRules::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
        ], [
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.max' => 'Password terlalu panjang',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.mixed' => 'Password harus mengandung huruf besar dan kecil',
            'password.numbers' => 'Password harus mengandung angka',
            'password.symbols' => 'Password harus mengandung simbol',
        ]);

        if ($validator->fails()) {
            RateLimiter::hit($throttleKey);
            return back()->withErrors($validator)->withInput();
        }

        // Sanitasi input
        $email = filter_var($request->email, FILTER_SANITIZE_EMAIL);
        $token = filter_var($request->token, FILTER_SANITIZE_STRING);

        // Validasi token
        $resetToken = DB::table('password_reset_tokens')
                        ->where('email', $email)
                        ->where('used', false)
                        ->where('expires_at', '>', now())
                        ->first();

        if (!$resetToken || !Hash::check($token, $resetToken->token)) {
            RateLimiter::hit($throttleKey);
            return back()->withErrors(['email' => 'Token tidak valid atau sudah kadaluarsa.']);
        }

        // Cek user
        $user = User::where('email', $email)->first();
        if (!$user) {
            RateLimiter::hit($throttleKey);
            return back()->withErrors(['email' => 'User tidak ditemukan.']);
        }

        // Update password
        try {
            DB::beginTransaction();
            
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            // Mark token as used
            DB::table('password_reset_tokens')
                ->where('email', $email)
                ->update(['used' => true]);

            DB::commit();

            RateLimiter::hit($throttleKey);
            return redirect()->route('login')->with('status', 'Password berhasil direset. Silakan login dengan password baru.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Password reset failed: ' . $e->getMessage());
            
            RateLimiter::hit($throttleKey);
            return back()->withErrors(['password' => 'Gagal mereset password. Silakan coba lagi.']);
        }
    }

    /**
     * API method untuk reset password
     */
    public function apiSendResetLink(Request $request)
    {
        // Rate limiting untuk API
        $throttleKey = 'api-password-reset:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            return response()->json([
                'success' => false,
                'message' => 'Terlalu banyak percobaan. Silakan coba lagi nanti.'
            ], 429);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255'
        ]);

        if ($validator->fails()) {
            RateLimiter::hit($throttleKey);
            return response()->json([
                'success' => false,
                'message' => 'Email tidak valid'
            ], 400);
        }

        $email = filter_var($request->email, FILTER_SANITIZE_EMAIL);
        $user = User::where('email', $email)->first();

        if (!$user) {
            RateLimiter::hit($throttleKey);
            return response()->json([
                'success' => true,
                'message' => 'Jika email terdaftar, link reset password akan dikirim.'
            ]);
        }

        // Generate dan kirim token
        $token = Str::random(64);
        $expiresAt = now()->addMinutes(60);

        DB::table('password_reset_tokens')->where('email', $email)->delete();

        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => Hash::make($token),
            'created_at' => now(),
            'expires_at' => $expiresAt,
            'used' => false,
            'request_ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        try {
            Mail::send('emails.reset-password', [
                'user' => $user,
                'token' => $token,
                'expiresAt' => $expiresAt
            ], function ($message) use ($user) {
                $message->to($user->email, $user->name)
                        ->subject('Reset Password - E-Prescription System');
            });

            RateLimiter::hit($throttleKey);
            return response()->json([
                'success' => true,
                'message' => 'Link reset password telah dikirim ke email Anda.'
            ]);

        } catch (\Exception $e) {
            Log::error('API Password reset email failed: ' . $e->getMessage());
            
            RateLimiter::hit($throttleKey);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email. Silakan coba lagi.'
            ], 500);
        }
    }
}
