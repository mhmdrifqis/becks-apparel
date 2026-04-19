<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Redirect based on role
        if ($user->hasRole('Admin')) {
            return redirect()->intended(route('filament.admin.pages.dashboard', absolute: false));
        }

        if ($user->hasRole('Tim Produksi')) {
            return redirect()->intended(route('filament.produksi.pages.dashboard', absolute: false));
        }

        if ($user->hasRole('Management/Owner')) {
            return redirect()->intended(route('filament.owner.pages.dashboard', absolute: false));
        }

        // Default redirect for customers is Home
        return redirect()->intended(url('/'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
