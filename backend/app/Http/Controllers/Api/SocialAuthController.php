<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect to Google OAuth provider.
     * Returns the Google OAuth URL as JSON so the frontend can redirect.
     */
    public function redirectToGoogle(): JsonResponse
    {
        try {
            $url = Socialite::driver('google')
                ->stateless()
                ->redirect()
                ->getTargetUrl();

            return response()->json(['url' => $url]);
        } catch (\Exception $e) {
            Log::error('Google OAuth redirect error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to generate Google OAuth URL.'], 500);
        }
    }

    /**
     * Handle Google OAuth callback.
     * Called from frontend after user authorizes Google login.
     * Accepts the authorization code and exchanges it for a user token.
     */
    public function handleGoogleCallback(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        try {
            // Exchange authorization code for Google user info
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->user();

            // Find or create the user based on Google ID or email
            $user = User::where('google_id', $googleUser->getId())->first();

            if (!$user) {
                // Try finding by email (for users who already registered manually)
                $user = User::where('email', $googleUser->getEmail())->first();

                if ($user) {
                    // Link Google ID to existing account
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $user->avatar ?? $googleUser->getAvatar(),
                    ]);
                } else {
                    // Create brand new user from Google profile
                    $user = User::create([
                        'name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                        'password' => \Illuminate\Support\Str::random(32), // Random secure password
                        'email_verified_at' => now(), // Google emails are pre-verified
                        'role' => 'customer',
                    ]);
                }
            } else {
                // Update avatar if changed
                $user->update([
                    'avatar' => $googleUser->getAvatar(),
                ]);
            }

            // Issue Sanctum token
            $token = $user->createToken('google-oauth-token')->plainTextToken;

            return response()->json([
                'message' => 'Login dengan Google berhasil!',
                'user' => $user->fresh(),
                'token' => $token,
            ]);

        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            Log::error('Google OAuth invalid state: ' . $e->getMessage());
            return response()->json(['message' => 'OAuth state tidak valid. Coba login lagi.'], 422);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            Log::error('Google OAuth client error: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal menghubungi server Google. Coba lagi.'], 422);
        } catch (\Exception $e) {
            Log::error('Google OAuth error: ' . $e->getMessage());
            return response()->json(['message' => 'Login Google gagal: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Handle the token-based flow where frontend sends the Google access token directly.
     * This is used when using @react-oauth/google library on frontend.
     */
    public function handleGoogleToken(Request $request): JsonResponse
    {
        $request->validate([
            'access_token' => 'required_without:credential|string',
            'credential'   => 'required_without:access_token|string',
        ]);

        try {
            $googleUser = null;

            if ($request->has('credential')) {
                // Google One Tap / ID Token flow (JWT credential)
                $idToken = $request->input('credential');
                $googleUser = $this->verifyGoogleIdToken($idToken);

                if (!$googleUser) {
                    return response()->json(['message' => 'Google ID token tidak valid.'], 422);
                }
            } else {
                // Standard OAuth access_token flow via Socialite
                $googleUser = Socialite::driver('google')
                    ->stateless()
                    ->userFromToken($request->input('access_token'));
            }

            // Safely extract Google user profile attributes
            if (is_array($googleUser)) {
                $googleId = $googleUser['sub'] ?? ($googleUser['id'] ?? null);
                $email    = $googleUser['email'] ?? null;
                $name     = $googleUser['name'] ?? 'User';
                $avatar   = $googleUser['picture'] ?? null;
            } else {
                $googleId = $googleUser->getId();
                $email    = $googleUser->getEmail();
                $name     = $googleUser->getName();
                $avatar   = $googleUser->getAvatar();
            }

            if (!$email) {
                return response()->json(['message' => 'Gagal mendapatkan email dari akun Google.'], 422);
            }

            // Find or create user
            $user = User::where('google_id', $googleId)->first();

            if (!$user) {
                $user = User::where('email', $email)->first();

                if ($user) {
                    $user->update([
                        'google_id' => $googleId,
                        'avatar' => $user->avatar ?? $avatar,
                    ]);
                } else {
                    $user = User::create([
                        'name' => $name,
                        'email' => $email,
                        'google_id' => $googleId,
                        'avatar' => $avatar,
                        'password' => \Illuminate\Support\Str::random(32),
                        'email_verified_at' => now(),
                        'role' => 'customer',
                    ]);
                }
            } else {
                if ($avatar) {
                    $user->update(['avatar' => $avatar]);
                }
            }

            $token = $user->createToken('google-oauth-token')->plainTextToken;

            // Redirect admin to Filament
            $redirectUrl = null;
            if ($user->role === 'admin') {
                $redirectUrl = config('app.url') . '/admin';
            }

            return response()->json([
                'message' => 'Login dengan Google berhasil!',
                'user' => $user->fresh(),
                'token' => $token,
                'redirect_url' => $redirectUrl,
            ]);

        } catch (\Exception $e) {
            Log::error('Google token auth error: ' . $e->getMessage());
            return response()->json(['message' => 'Verifikasi token Google gagal: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Verify a Google ID Token (JWT) using Google's tokeninfo endpoint.
     */
    private function verifyGoogleIdToken(string $idToken): ?array
    {
        try {
            $response = \Illuminate\Support\Facades\Http::get('https://oauth2.googleapis.com/tokeninfo', [
                'id_token' => $idToken,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Verify the audience matches our client ID
                if ($data['aud'] !== config('services.google.client_id')) {
                    Log::warning('Google ID token audience mismatch');
                    return null;
                }

                return $data;
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Google ID token verification error: ' . $e->getMessage());
            return null;
        }
    }
}
