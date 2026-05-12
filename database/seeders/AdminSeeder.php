<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'KmX11e@PmX.com'],
            [
                'name' => 'Директор',
                'password' => Hash::make('PaMOe12_61//Ej'),
                'role' => 'director',
            ]
        );

        User::updateOrCreate(
            ['email' => 'KrEat11@main.com'],
            [
                'name' => 'Контент-менеджер',
                'password' => Hash::make('KreAte11#'),
                'role' => 'content_manager',
            ]
        );

        User::updateOrCreate(
            ['email' => 'MAzo22@main.com'],
            [
                'name' => 'Менеджер',
                'password' => Hash::make('MoKXt23#'),
                'role' => 'manager',
            ]
        );
    }
}
