<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'login_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'logo.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
        }

        // Handle login background image upload
        if ($request->hasFile('login_image')) {
            $file = $request->file('login_image');
            $filename = 'login-bg.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
        }

        // For now, we'll store settings in config or database
        // You can extend this to save to database later
        $settings = [
            'business_name' => $request->business_name,
            'logo_updated' => $request->hasFile('logo') ? now() : null,
            'login_image_updated' => $request->hasFile('login_image') ? now() : null,
        ];

        // Save to a simple JSON file for now
        file_put_contents(storage_path('app/settings.json'), json_encode($settings));

        return back()->with('success', 'Settings updated successfully.');
    }

    public static function getBusinessName()
    {
        $settingsFile = storage_path('app/settings.json');
        if (file_exists($settingsFile)) {
            $settings = json_decode(file_get_contents($settingsFile), true);
            return $settings['business_name'] ?? 'CHENG KELLO';
        }
        return 'CHENG KELLO';
    }
}