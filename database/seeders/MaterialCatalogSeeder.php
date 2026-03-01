<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\MaterialType;
use App\Models\Manufacturer;
use Illuminate\Database\Seeder;

class MaterialCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            MaterialType::firstOrCreate(['name' => 'Сайдинг']),
            MaterialType::firstOrCreate(['name' => 'Фасадные панели']),
            MaterialType::firstOrCreate(['name' => 'Софиты']),
            MaterialType::firstOrCreate(['name' => 'Террасная доска']),
            MaterialType::firstOrCreate(['name' => 'Водосток']),
        ];

        $manufacturers = [
            Manufacturer::firstOrCreate(['name' => 'Альта Профиль']),
            Manufacturer::firstOrCreate(['name' => 'Docke']),
            Manufacturer::firstOrCreate(['name' => 'Гранд Лайн']),
        ];

        $alta = $manufacturers[0];

        Material::firstOrCreate(
            [
                'material_type_id' => $types[0]->id,
                'manufacturer_id' => $alta->id,
                'product_name' => 'Аляска Ивори',
                'color' => 'Ивори',
                'dimensions' => '3 x 0,205 м',
                'price' => 240,
            ]
        );

        Material::firstOrCreate(
            [
                'material_type_id' => $types[0]->id,
                'manufacturer_id' => $alta->id,
                'product_name' => 'Гарден',
                'color' => 'Гарден',
                'dimensions' => '3 x 0,205 м',
                'price' => 240,
            ]
        );
    }
}
