<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function login(): View
    {
        return view('auth.login');
    }

    public function register(): View
    {
        return view('auth.register');
    }

    public function storeRegistration(RegisterRequest $request): RedirectResponse
    {
        $user = new User($request->safe()->only(['name', 'email', 'password']));
        $user->role()->associate(Role::where('name', 'pengguna')->firstOrFail());
        $user->save();

        event(new Registered($user));
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('account')->with('success', 'Akun berhasil dibuat. Selamat datang!');
    }

    public function storeLogin(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // Fixed internal destinations avoid forwarding users to forbidden admin URLs.
        return redirect()->route($request->user()->isAdmin() ? 'admin.dashboard' : 'account');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Kamu berhasil keluar.');
    }
}
