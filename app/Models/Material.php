<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Material extends Model
{
    protected $fillable = [
        'material_type_id',
        'manufacturer_id',
        'product_name',
        'color',
        'dimensions',
        'dimensions_unit',
        'description',
        'image_path',
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

    /** Краткое описание для вывода в таблицах */
    public function getDisplayNameAttribute(): string
    {
        $type = $this->materialType?->name ?? '—';
        $manuf = $this->manufacturer?->name ?? '—';
        $priceLabel = number_format((float) $this->price, 0, '.', ',');

        return "{$type}, {$manuf}, {$this->product_name}" . ($this->dimensions_label ? ", {$this->dimensions_label}" : '') . ", {$priceLabel} ₽";
    }

    public function getDimensionsLabelAttribute(): ?string
    {
        $dims = trim((string) ($this->dimensions ?? ''));
        if ($dims === '') {
            return null;
        }

        $unit = trim((string) ($this->dimensions_unit ?? ''));
        return $unit !== '' ? "{$dims} {$unit}" : $dims;
    }

    /** URL изображения товара (файл в storage или внешняя ссылка) */
    public function getImageUrlAttribute(): ?string
    {
        $p = $this->image_path;
        if ($p === null || $p === '') {
            return null;
        }
        if (str_starts_with($p, 'http://') || str_starts_with($p, 'https://')) {
            return $p;
        }

        return asset($p);
    }
}

