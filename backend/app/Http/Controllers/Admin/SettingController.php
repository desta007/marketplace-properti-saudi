<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display settings form
     */
    public function index()
    {
        $contactSettings = Setting::getByGroup('contact');
        $generalSettings = Setting::getByGroup('general');

        // Get values with defaults
        $settings = [
            'site_name' => Setting::get('site_name', 'SaudiProp'),
            'site_email' => Setting::get('site_email', 'info@saudiprop.com'),
            'site_phone' => Setting::get('site_phone', '+966 50 123 4567'),
            'site_address' => Setting::get('site_address', 'Riyadh, Saudi Arabia'),
            'site_whatsapp' => Setting::get('site_whatsapp', ''),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'nullable|string|max:255',
            'site_email' => 'nullable|email|max:255',
            'site_phone' => 'nullable|string|max:50',
            'site_address' => 'nullable|string|max:500',
            'site_whatsapp' => 'nullable|string|max:50',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully!');
    }
}
