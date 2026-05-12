<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contacts');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string|max:5000',
        ]);

        if ($request->user()?->isCustomer()) {
            if ($request->user()->email !== $data['email']) {
                return back()->withErrors(['email' => 'Укажите email, привязанный к вашему аккаунту.'])->withInput();
            }
            $data['user_id'] = $request->user()->id;
        }

        ContactMessage::create($data);

        return back()->with('success', 'Спасибо! Ваше сообщение принято — менеджер свяжется с вами в ближайшее время.');
    }
}
