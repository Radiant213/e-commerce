<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'phone' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'phone' => $validated['phone'] ?? null,
        ]);

        $user->sendEmailVerificationNotification();

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil! Silakan cek email Anda untuk memverifikasi akun.',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($validated)) {
            return response()->json([
                'message' => 'Email atau password salah.',
            ], 401);
        }

        $user = User::where('email', $validated['email'])->first();
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil!',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil.',
        ]);
    }

    public function user(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:1000',
            'avatar' => 'nullable|string|max:1000',
            'avatar_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120', // 5MB max
        ]);

        $user = $request->user();

        // Handle file upload if provided
        if ($request->hasFile('avatar_file')) {
            $file = $request->file('avatar_file');
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('avatars', $filename, 'public');
            $validated['avatar'] = $path;
            unset($validated['avatar_file']);
        }

        $user->update($validated);

        return response()->json([
            'message' => 'Profil dan avatar berhasil diperbarui.',
            'user' => $user->fresh(),
        ]);
    }

    public function updatePassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        if (!Hash::check($validated['current_password'], $request->user()->password)) {
            return response()->json([
                'message' => 'Kata sandi saat ini tidak cocok.',
            ], 422);
        }

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'message' => 'Kata sandi berhasil diperbarui.',
        ]);
    }

    public function createPanelLink(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'panel' => 'required|string|in:admin,cms',
        ]);

        $user = $request->user();
        $panel = $validated['panel'];

        if ($panel === 'admin' && ! $user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak: Hanya admin yang dapat mengakses panel admin.',
            ], 403);
        }

        if ($panel === 'cms' && ! $user->canManageCms()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak: Anda tidak memiliki akses ke studio konten.',
            ], 403);
        }

        $token = \Illuminate\Support\Str::random(64);
        \Illuminate\Support\Facades\Cache::put("panel_sso:{$token}", [
            'user_id' => $user->id,
            'panel' => $panel,
        ], now()->addSeconds(60));

        $url = url("/auth/panel-sso?token={$token}");

        return response()->json([
            'success' => true,
            'data' => [
                'url' => $url,
            ],
        ]);
    }
}
