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
        $frontendUrl = config('app.frontend_url', 'https://demo1-ecommerce.radiantcode.web.id');
        $user = User::find($id);

        if (!$user) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'User tidak ditemukan'], 404);
            }
            return redirect("{$frontendUrl}/login?error=" . urlencode('Akun pengguna tidak ditemukan.'));
        }

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Link tidak valid atau kadaluarsa.'], 403);
            }
            return redirect("{$frontendUrl}/login?error=" . urlencode('Link verifikasi tidak valid atau telah kadaluarsa.'));
        }

        if (!$user->hasVerifiedEmail()) {
            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
            }
        }

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Email berhasil diverifikasi.'
            ]);
        }

        return redirect("{$frontendUrl}/login?verified=1");
    }
}
