<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    protected $fillable = [
        'material_type_id',
        'manufacturer_id',
        'product_name',
        'color',
        'dimensions',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /** Первичный ключ: id. Внешние ключи: material_type_id, manufacturer_id */

    public function materialType(): BelongsTo
    {
        return $this->belongsTo(MaterialType::class, 'material_type_id');
    }

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class, 'manufacturer_id');
    }

    /** Остатки по складам */
    public function warehouseStocks(): HasMany
    {
        return $this->hasMany(WarehouseStock::class, 'material_id');
    }

    /** Краткое описание для вывода в таблицах */
    public function getDisplayNameAttribute(): string
    {
        $type = $this->materialType?->name ?? '—';
        $manuf = $this->manufacturer?->name ?? '—';
        return "{$type}, {$manuf}, {$this->product_name}" . ($this->dimensions ? ", {$this->dimensions}" : '') . ", {$this->price} ₽";
    }
}
