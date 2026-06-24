<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    // ── Register ──────────────────────────────────────────
    public function showRegister()
    {
        if (Auth::check()) return redirect()->route('products.index');
        return view('auth.customer-register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('products.index')
            ->with('success', __('Account created successfully! Welcome to Happy Meal!'));
    }

    // ── Login ─────────────────────────────────────────────
    public function showLogin()
    {
        if (Auth::check()) return redirect()->route('products.index');
        return view('auth.customer-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('products.index'))
                ->with('success', __('Welcome back!') . ' ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'email' => __('These credentials do not match our records.'),
        ])->withInput($request->except('password'));
    }

    // ── Logout ────────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('products.index')
            ->with('success', __('You have been logged out.'));
    }
}
