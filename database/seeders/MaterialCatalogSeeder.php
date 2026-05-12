<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\MaterialType;
use App\Models\Manufacturer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MaterialCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $typeNames = [
            'Битумная плитка',
            'Искусственный камень',
            'Панели ПВХ',
            'Сайдинг металлический',
            'Сайдинг ПВХ',
            'Система отделки углов и окон',
            'Фиброцементный сайдинг',
            'Система крепления фасадов ПВХ',
            'Термопанели',
            'Система крепления фасадов',
        ];

        $types = collect($typeNames)->map(function (string $name) {
            return MaterialType::updateOrCreate(
                ['name' => $name],
                ['slug' => Str::slug($name), 'category_slug' => 'facade']
            );
        })->all();

        $manufacturers = [
            Manufacturer::updateOrCreate(['name' => 'Альта Профиль'], ['logo_path' => null]),
            Manufacturer::updateOrCreate(['name' => 'Docke'], ['logo_path' => null]),
            Manufacturer::updateOrCreate(['name' => 'Гранд Лайн'], ['logo_path' => null]),
        ];

        $alta = $manufacturers[0];

        Material::firstOrCreate(
            [
                'material_type_id' => $types[0]->id,
                'manufacturer_id' => $alta->id,
                'product_name' => 'Аляска Ивори',
                'color' => 'Ивори',
                'dimensions' => '3 x 0,205 м',
                'image_path' => null,
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
