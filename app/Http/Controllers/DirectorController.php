<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DirectorController extends Controller
{
    public function dashboard()
    {
        $contentCreator = User::query()->where('role', 'content_manager')->orderBy('id')->first();
        $managerUser = User::query()->where('role', 'manager')->orderBy('id')->first();

        return view('director.dashboard', compact('contentCreator', 'managerUser'));
    }

    public function updateCredentials(Request $request, User $user)
    {
        abort_unless(in_array($user->role, ['content_manager', 'manager'], true), 403);

        $data = $request->validate([
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'required|string|min:8',
        ]);

        $user->update([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return back()->with('success', 'Данные для выбранной роли обновлены.');
    }
}
