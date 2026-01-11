<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Get all settings grouped by their group.
     */
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        
        // Flatten for easier frontend use if needed, or keep grouped
        $formatted = [];
        foreach (Setting::all() as $setting) {
            $formatted[$setting->key] = $setting->value;
        }

        return response()->json([
            'data' => $formatted,
            'grouped' => $settings
        ]);
    }

    /**
     * Update multiple settings at once.
     */
    public function updateBulk(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($request->settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return response()->json(['message' => 'Settings updated successfully']);
    }

    /**
     * Get settings for a specific group.
     */
    public function getByGroup($group)
    {
        $settings = Setting::where('group', $group)->get()->pluck('value', 'key');
        return response()->json(['data' => $settings]);
    }
}
