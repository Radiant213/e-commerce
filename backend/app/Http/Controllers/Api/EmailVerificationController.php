<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;

class EmailVerificationController extends Controller
{
    /**
     * Resend the email verification notification.
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Email sudah terverifikasi.'
            ]);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'status' => 'success',
            'message' => 'Link verifikasi telah dikirim ulang ke email Anda.'
        ]);
    }

    /**
     * Verify the user's email address.
     */
    public function verify(Request $request, $id, $hash)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'Link tidak valid atau kadaluarsa.'], 403);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Email sudah terverifikasi.'
            ]);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Email berhasil diverifikasi.'
        ]);
    }
}
