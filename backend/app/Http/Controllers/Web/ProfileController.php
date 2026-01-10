<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Show profile page
     */
    public function index()
    {
        return view('web.profile.index');
    }

    /**
     * Update profile
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'language' => 'nullable|in:ar,en',
        ]);

        $user = auth()->user();
        $user->update($request->only(['name', 'phone', 'whatsapp_number', 'language']));

        // Update locale
        if ($request->language) {
            session()->put('locale', $request->language);
            app()->setLocale($request->language);
        }

        return back()->with('success', 'Profile updated successfully');
    }
}
