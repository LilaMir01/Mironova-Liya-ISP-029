<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($validated, (bool) $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            $target = match ($user->role) {
                'director' => route('director.dashboard'),
                'content_manager' => route('products.index'),
                'content_creator' => route('products.index'),
                'manager' => route('manager.orders'),
                default => route('account.index'),
            };
            return redirect()->intended($target);
        }

        return back()->withErrors([
            'email' => 'Неверный email или пароль.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Вы вышли из системы.');
    }
}
