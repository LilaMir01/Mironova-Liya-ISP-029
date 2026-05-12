<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CatalogController extends Controller
{
    /**
     * Фон карточки на странице «Продукция»: файл в public/images/catalog/{slug}.(jpg|jpeg|png|webp).
     * Имя файла = slug раздела (латиница), напр. images/catalog/sajding-pvh.jpg
     *
     * @return string|null путь относительно public для asset(), либо null
     */
    public static function catalogCardBackgroundRelativePath(string $slug): ?string
    {
        $dir = 'images/catalog';
        foreach (['.jpg', '.jpeg', '.png', '.webp'] as $ext) {
            $relative = $dir.'/'.$slug.$ext;
            if (is_file(public_path($relative))) {
                return $relative;
            }
        }

        return null;
    }

    /**
     * Фасадные подразделы: порядок на странице «Продукция» и в мегаменю.
     *
     * @return list<string>
     */
    public static function facadeSectionNames(): array
    {
        return [
            'Сайдинг ПВХ',
            'Панели ПВХ',
            'Сайдинг металлический',
            'Битумная плитка',
            'Искусственный камень',
            'Система отделки углов и окон',
            'Фиброцементный сайдинг',
            'Система крепления фасадов ПВХ',
            'Термопанели',
            'Система крепления фасадов',
        ];
    }

    public function index()
    {
        $facadeSections = collect(self::facadeSectionNames())->map(function (string $name) {
            $slug = Str::slug($name);
            $bg = self::catalogCardBackgroundRelativePath($slug);

            return [
                'name' => $name,
                'slug' => $slug,
                'background_url' => $bg !== null ? asset($bg) : null,
            ];
        })->all();

        $otherCategories = collect([
            ['slug' => 'floor', 'name' => 'Напольные покрытия'],
            ['slug' => 'terrace', 'name' => 'Террасная доска'],
            ['slug' => 'drainage', 'name' => 'Водосточные системы'],
            ['slug' => 'ventilation', 'name' => 'Вентиляция'],
            ['slug' => 'insulation', 'name' => 'Утеплитель'],
            ['slug' => 'isolation', 'name' => 'Изоляция'],
        ])->map(function (array $row) {
            $bg = self::catalogCardBackgroundRelativePath($row['slug']);

            return array_merge($row, [
                'background_url' => $bg !== null ? asset($bg) : null,
            ]);
        })->all();

        return view('catalog.index', compact('facadeSections', 'otherCategories'));
    }

    public function category(Request $request, string $category)
    {
        if ($category === 'facade') {
            return redirect()->route('catalog.index');
        }

        $titles = [
            'floor' => 'Напольные покрытия',
            'terrace' => 'Террасная доска',
            'drainage' => 'Водосточные системы',
            'ventilation' => 'Вентиляция',
            'insulation' => 'Утеплитель и изоляция',
        ];

        $categoryTitle = $titles[$category] ?? ucfirst($category);

        $minPrice = 0;
        $maxPrice = 100000;
        $priceFrom = (int) $request->get('price_from', $minPrice);
        $priceTo = (int) $request->get('price_to', $maxPrice);

        $priceFrom = max($minPrice, min($priceFrom, $maxPrice));
        $priceTo = max($minPrice, min($priceTo, $maxPrice));
        if ($priceFrom > $priceTo) {
            [$priceFrom, $priceTo] = [$priceTo, $priceFrom];
        }

        $materials = collect(); // Пока карточек нет, показываем только шаблон с фильтром

        return view('catalog.category-products', compact(
            'categoryTitle',
            'materials',
            'priceFrom',
            'priceTo',
            'minPrice',
            'maxPrice'
        ));
    }

    public function section(Request $request, string $category, string $sectionSlug)
    {
        abort_unless($category === 'facade', 404);

        $section = MaterialType::where('slug', $sectionSlug)->firstOrFail();
        $baseQuery = Material::where('material_type_id', $section->id);

        $minPrice = (int) ($baseQuery->min('price') ?? 0);
        $maxPrice = (int) ($baseQuery->max('price') ?? 100000);
        if ($maxPrice < $minPrice) {
            $minPrice = 0;
            $maxPrice = 100000;
        }

        $priceFrom = (int) $request->get('price_from', $minPrice);
        $priceTo = (int) $request->get('price_to', $maxPrice);
        $manufacturerId = $request->get('manufacturer_id') ? (int) $request->get('manufacturer_id') : null;

        $priceFrom = max($minPrice, min($priceFrom, $maxPrice));
        $priceTo = max($minPrice, min($priceTo, $maxPrice));
        if ($priceFrom > $priceTo) {
            [$priceFrom, $priceTo] = [$priceTo, $priceFrom];
        }

        $materialsQuery = Material::with('manufacturer')
            ->where('material_type_id', $section->id)
            ->whereBetween('price', [$priceFrom, $priceTo]);

        if ($manufacturerId) {
            $materialsQuery->where('manufacturer_id', $manufacturerId);
        }

        $materials = $materialsQuery->orderBy('product_name')->get();

        $catalogProductsModalData = $materials->map(function (Material $m) {
            return [
                'id' => $m->id,
                'name' => $m->product_name,
                'priceFormatted' => number_format((float) $m->price, 0, '.', ',') . ' ₽',
                'color' => $m->color ?? '',
                'manufacturer' => $m->manufacturer?->name ?? '',
                'description' => $m->description ?? '',
                'image' => $m->image_url,
                'dimensions' => $m->dimensions_label ?? '',
                'cartUrl' => route('cart.add', $m),
            ];
        })->values()->all();

        $manufacturers = \App\Models\Manufacturer::whereIn('id', function ($query) use ($section) {
            $query->select('manufacturer_id')
                ->from('materials')
                ->where('material_type_id', $section->id)
                ->distinct();
        })->orderBy('name')->get();

        return view('catalog.section-products', compact(
            'section',
            'materials',
            'catalogProductsModalData',
            'manufacturers',
            'priceFrom',
            'priceTo',
            'minPrice',
            'maxPrice',
            'manufacturerId'
        ));
    }
}
