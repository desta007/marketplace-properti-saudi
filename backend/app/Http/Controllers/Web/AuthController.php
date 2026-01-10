<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('web.auth.login');
    }

    /**
     * Show registration form
     */
    public function showRegister()
    {
        return view('web.auth.register');
    }

    /**
     * Handle login submission
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        $email = strtolower(trim($request->email));
        $remember = $request->boolean('remember');

        // Attempt to authenticate
        if (Auth::attempt(['email' => $email, 'password' => $request->password], $remember)) {
            $request->session()->regenerate();

            return redirect()->intended('/')
                ->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email', 'remember'));
    }

    /**
     * Handle registration submission
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|min:10|max:20',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $email = strtolower(trim($request->email));
        $name = trim($request->name);
        $phone = trim($request->phone);

        // Create new user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'whatsapp_number' => $phone,
            'password' => Hash::make($request->password),
            'email_verified_at' => Carbon::now(),
            'language' => app()->getLocale(),
            'role' => 'user',
        ]);

        // Login the user
        Auth::login($user, true);

        return redirect()->intended('/')
            ->with('success', 'Welcome to SaudiProp, ' . $user->name . '!');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out.');
    }
}
