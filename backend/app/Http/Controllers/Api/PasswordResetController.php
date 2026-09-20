<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\PasswordReset;
use App\Models\User;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * Send password reset link to email.
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Kami tidak menemukan akun dengan email tersebut.'
        ]);

        // Generate custom token instead of default Laravel Password broker to use our custom mail
        $user = User::where('email', $request->email)->first();
        $token = Password::getRepository()->create($user);

        // Send custom email
        Mail::to($user->email)->send(new PasswordResetMail($token, $user->email));

        return response()->json([
            'status' => 'success',
            'message' => 'Link reset kata sandi telah dikirim ke email Anda.'
        ]);
    }

    /**
     * Reset password using token.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'status' => 'success',
                'message' => 'Kata sandi berhasil diatur ulang. Silakan login dengan kata sandi baru.'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Token reset kata sandi tidak valid atau kadaluarsa.'
        ], 400);
    }
}
