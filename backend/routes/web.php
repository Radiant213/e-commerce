<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/auth/google/callback', function () {
    try {
        /** @var \Laravel\Socialite\Two\AbstractProvider $driver */
        $driver = Socialite::driver('google');
        $googleUser = $driver->stateless()->user();

        $user = User::where('google_id', $googleUser->getId())->first();
        if (!$user) {
            $user = User::where('email', $googleUser->getEmail())->first();
            if ($user) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $user->avatar ?? $googleUser->getAvatar(),
                ]);
            } else {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => \Illuminate\Support\Str::random(32),
                    'email_verified_at' => now(),
                    'role' => 'customer',
                ]);
            }
        } else {
            if ($googleUser->getAvatar()) {
                $user->update(['avatar' => $googleUser->getAvatar()]);
            }
        }

        $token = $user->createToken('google-oauth-token')->plainTextToken;

        if ($user->role === 'admin') {
            return redirect('/admin');
        }

        $frontendUrl = config('app.frontend_url');
        return redirect("{$frontendUrl}/login?token={$token}&oauth=success");
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Google callback error: ' . $e->getMessage());
        $frontendUrl = config('app.frontend_url');
        return redirect("{$frontendUrl}/login?error=" . urlencode($e->getMessage()));
    }
});

Route::middleware(['web'])->group(function () {
    Route::get('/admin/orders/{order}/invoice', [\App\Http\Controllers\Admin\ReportPrintController::class, 'invoice'])->name('admin.orders.invoice');
    Route::get('/admin/reports/print/sales', [\App\Http\Controllers\Admin\ReportPrintController::class, 'salesReport'])->name('admin.reports.print.sales');
    Route::get('/admin/reports/print/inventory', [\App\Http\Controllers\Admin\ReportPrintController::class, 'inventoryReport'])->name('admin.reports.print.inventory');
});
