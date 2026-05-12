<?php

namespace App\Http\Controllers;

use App\Models\HomeSlide;
use App\Models\Material;
use App\Models\MaterialType;
use App\Models\Manufacturer;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Material::with('materialType', 'manufacturer')->orderBy('product_name');

        if ($request->filled('filter_material_type_id')) {
            $query->where('material_type_id', (int) $request->input('filter_material_type_id'));
        }
        if ($request->filled('filter_manufacturer_id')) {
            $query->where('manufacturer_id', (int) $request->input('filter_manufacturer_id'));
        }
        if ($request->filled('filter_product_name')) {
            $query->where('product_name', $request->input('filter_product_name'));
        }

        $materials = $query->paginate(15)->withQueryString();

        $materialTypes = MaterialType::orderBy('name')->get();
        $manufacturers = Manufacturer::orderBy('name')->get();

        $productNamesForFilter = Material::query()
            ->distinct()
            ->orderBy('product_name')
            ->pluck('product_name')
            ->values();

        $editMaterial = null;
        if ($request->has('edit')) {
            $editMaterial = Material::with('materialType', 'manufacturer')->find($request->input('edit'));
        }

        $homeSlides = HomeSlide::query()->orderBy('slot')->get();

        return view('products', compact(
            'materials',
            'materialTypes',
            'manufacturers',
            'editMaterial',
            'homeSlides',
            'productNamesForFilter'
        ));
    }

    public function storeMaterialType(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_slug' => 'nullable|string|max:255',
        ]);

        MaterialType::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'category_slug' => $data['category_slug'] ?: 'facade',
            'image_path' => null,
        ]);

        return redirect()->route('products.index')->with('success', 'Материал добавлен.');
    }

    public function storeManufacturer(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:5120',
        ]);

        $logoPath = $this->storePublicImage($request->file('logo'), 'manufacturer-logos');

        Manufacturer::create([
            'name' => $data['name'],
            'logo_path' => $logoPath,
        ]);

        return redirect()->route('products.index')->with('success', 'Производитель добавлен.');
    }

    public function storeMaterial(Request $request)
    {
        $data = $request->validate([
            'material_type_id' => 'required_without:new_material_type|nullable|exists:material_types,id',
            'new_material_type' => 'nullable|string|max:255',
            'manufacturer_id' => 'required_without:new_manufacturer|nullable|exists:manufacturers,id',
            'new_manufacturer' => 'nullable|string|max:255',
            'product_name' => 'required|string|max:255',
            'color' => 'nullable|string|max:255',
            'dimensions' => 'nullable|string|max:255',
            'dimensions_unit' => 'nullable|in:mm,см,м,м²,м³',
            'description' => 'nullable|string|max:20000',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:5120',
        ]);

        if (! empty($data['new_material_type'])) {
            $type = MaterialType::firstOrCreate(
                ['name' => $data['new_material_type']],
                ['slug' => Str::slug($data['new_material_type']), 'category_slug' => 'facade']
            );
            $data['material_type_id'] = $type->id;
        }
        if (! empty($data['new_manufacturer'])) {
            $manuf = Manufacturer::firstOrCreate(['name' => $data['new_manufacturer']]);
            $data['manufacturer_id'] = $manuf->id;
        }

        unset($data['new_material_type'], $data['new_manufacturer'], $data['image']);

        $data['image_path'] = $this->storePublicImage($request->file('image'), 'materials');

        Material::create($data);

        return redirect()->route('products.index')->with('success', 'Товар добавлен в справочник.');
    }

    public function updateMaterial(Request $request, Material $material)
    {
        $data = $request->validate([
            'material_type_id' => 'required|exists:material_types,id',
            'manufacturer_id' => 'required|exists:manufacturers,id',
            'product_name' => 'required|string|max:255',
            'color' => 'nullable|string|max:255',
            'dimensions' => 'nullable|string|max:255',
            'dimensions_unit' => 'nullable|in:mm,см,м,м²,м³',
            'description' => 'nullable|string|max:20000',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $this->deletePublicPath($material->image_path);
            $data['image_path'] = $this->storePublicImage($request->file('image'), 'materials');
        }

        unset($data['image']);

        $material->update($data);

        return redirect()->route('products.index')->with('success', 'Товар обновлён.');
    }

    public function destroyMaterial(Material $material)
    {
        $this->deletePublicPath($material->image_path);
        $material->delete();

        return redirect()->back()->with('success', 'Товар удалён из справочника.');
    }

    public function destroyMaterialType(MaterialType $materialType)
    {
        $this->deletePublicPath($materialType->image_path);
        $materialType->delete();

        return redirect()->back()->with('success', 'Материал удалён.');
    }

    public function updateManufacturer(Request $request, Manufacturer $manufacturer)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('logo')) {
            $this->deletePublicPath($manufacturer->logo_path);
            $data['logo_path'] = $this->storePublicImage($request->file('logo'), 'manufacturer-logos');
        }

        unset($data['logo']);

        $manufacturer->update($data);

        return redirect()->route('products.index')->with('success', 'Производитель обновлён.');
    }

    public function destroyManufacturer(Manufacturer $manufacturer)
    {
        $this->deletePublicPath($manufacturer->logo_path);
        $manufacturer->delete();

        return redirect()->back()->with('success', 'Производитель удалён.');
    }

    private function storePublicImage(?UploadedFile $file, string $directory): ?string
    {
        if ($file === null || ! $file->isValid()) {
            return null;
        }

        $path = $file->store($directory, 'public');

        return 'storage/'.$path;
    }

    private function deletePublicPath(?string $path): void
    {
        if ($path === null || $path === '' || ! str_starts_with($path, 'storage/')) {
            return;
        }

        $relative = substr($path, strlen('storage/'));
        Storage::disk('public')->delete($relative);
    }
}
