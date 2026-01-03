<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        // If already authenticated as admin, redirect to dashboard
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    /**
     * Handle admin login.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt login using admin guard
        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::guard('admin')->user();

            // Check if user is admin
            if (!$user || !$user->isAdmin()) {
                Auth::guard('admin')->logout();
                return back()->withErrors([
                    'email' => __('admin.not_admin'),
                ])->onlyInput('email');
            }

            // Redirect to intended URL or dashboard
            $intendedUrl = session('intended', route('admin.dashboard'));
            session()->forget('intended');
            
            return redirect($intendedUrl)->with('success', __('admin.login_success'));
        }

        return back()->withErrors([
            'email' => __('admin.invalid_credentials'),
        ])->onlyInput('email');
    }

    /**
     * Handle admin logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
