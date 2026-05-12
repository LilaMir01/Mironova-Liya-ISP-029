<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $user = Auth::user();
        if (!$user instanceof User) {
            return redirect()->route('home');
        }
        if (!$user->isCustomer()) {
            return redirect()->route(match ($user->role) {
                'director' => 'director.dashboard',
                'content_manager' => 'products.index',
                'manager' => 'manager.orders',
                default => 'home',
            });
        }
        $orders = $user->orders()->orderByDesc('created_at')->get();
        $contactMessages = $user->contactMessages()->orderByDesc('created_at')->get();

        return view('account.index', compact('orders', 'contactMessages'));
    }
}
