<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialType;
use App\Models\Manufacturer;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $materials = Material::with('materialType', 'manufacturer')->orderBy('product_name')->get();
        $materialTypes = MaterialType::orderBy('name')->get();
        $manufacturers = Manufacturer::orderBy('name')->get();
        $editMaterial = null;
        if (request()->has('edit')) {
            $editMaterial = Material::with('materialType', 'manufacturer')->find(request('edit'));
        }

        return view('products', compact('materials', 'materialTypes', 'manufacturers', 'editMaterial'));
    }

    public function storeMaterialType(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        MaterialType::create(['name' => $request->name]);
        return redirect()->route('products.index')->with('success', 'Тип материала добавлен.');
    }

    public function storeManufacturer(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Manufacturer::create(['name' => $request->name]);
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
            'price' => 'required|numeric|min:0',
        ]);

        if (!empty($data['new_material_type'])) {
            $type = MaterialType::firstOrCreate(['name' => $data['new_material_type']]);
            $data['material_type_id'] = $type->id;
        }
        if (!empty($data['new_manufacturer'])) {
            $manuf = Manufacturer::firstOrCreate(['name' => $data['new_manufacturer']]);
            $data['manufacturer_id'] = $manuf->id;
        }

        unset($data['new_material_type'], $data['new_manufacturer']);
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
            'price' => 'required|numeric|min:0',
        ]);
        $material->update($data);
        return redirect()->route('products.index')->with('success', 'Товар обновлён.');
    }

    public function destroyMaterial(Material $material)
    {
        $material->delete();
        return redirect()->route('products.index')->with('success', 'Товар удалён из справочника.');
    }
}
