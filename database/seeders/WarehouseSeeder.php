<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        Warehouse::insert([
            ['name' => 'Сергиев Посад', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Хотьково', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
