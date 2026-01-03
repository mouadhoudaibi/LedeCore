<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated using admin guard
        if (!Auth::guard('admin')->check()) {
            // Store intended URL for redirect after login
            if (!$request->expectsJson()) {
                return redirect()->route('admin.login')->with('intended', $request->url());
            }
            return redirect()->route('admin.login');
        }

        // Check if user is admin
        $user = Auth::guard('admin')->user();
        if (!$user || !$user->isAdmin()) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('admin.login')->with('error', __('admin.not_admin'));
        }

        return $next($request);
    }
}
