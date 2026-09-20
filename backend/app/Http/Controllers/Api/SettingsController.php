<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Get public settings (e.g., shipping rates)
     */
    public function index()
    {
        $settings = Setting::whereIn('key', [
            'shipping_rate_regular',
            'shipping_rate_express'
        ])->get()->keyBy('key')->map(function ($setting) {
            return $setting->type === 'number' ? (float) $setting->value : $setting->value;
        });

        return response()->json([
            'settings' => $settings
        ]);
    }
}
