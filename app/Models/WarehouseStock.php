<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarehouseStock extends Model
{
    protected $table = 'warehouse_stock';

    protected $fillable = ['warehouse_id', 'material_id', 'quantity'];

    /** Первичный ключ: id. Внешние ключи: warehouse_id, material_id. Уникальность: (warehouse_id, material_id) */

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
}
