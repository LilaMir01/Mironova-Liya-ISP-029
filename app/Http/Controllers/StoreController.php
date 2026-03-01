<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::with(['stocks.material.materialType', 'stocks.material.manufacturer'])->orderBy('id')->get();
        $materials = Material::with('materialType', 'manufacturer')->orderBy('product_name')->get();
        $materialsJson = $materials->map(function ($m) {
            return [
                'id' => $m->id,
                'displayName' => $m->display_name,
                'type' => $m->materialType->name ?? '',
                'manufacturer' => $m->manufacturer->name ?? '',
                'productName' => $m->product_name,
                'color' => $m->color ?? '',
                'dimensions' => $m->dimensions ?? '',
            ];
        })->values();

        return view('stores', compact('warehouses', 'materials', 'materialsJson'));
    }

    public function storeStock(Request $request)
    {
        $data = $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'material_id' => 'required|exists:materials,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $stock = WarehouseStock::firstOrNew([
            'warehouse_id' => $data['warehouse_id'],
            'material_id' => $data['material_id'],
        ]);
        $stock->quantity = $stock->exists ? $stock->quantity + $data['quantity'] : $data['quantity'];
        $stock->save();

        return redirect()->route('stores.index')->with('success', 'Остаток добавлен на склад.');
    }

    public function updateStock(Request $request, WarehouseStock $stock)
    {
        $request->validate(['quantity' => 'required|integer|min:0']);
        $stock->update(['quantity' => $request->quantity]);
        return redirect()->route('stores.index')->with('success', 'Остаток обновлён.');
    }

    public function destroyStock(WarehouseStock $stock)
    {
        $stock->delete();
        return redirect()->route('stores.index')->with('success', 'Запись об остатке удалена.');
    }
}
