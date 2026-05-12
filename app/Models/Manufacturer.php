<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Manufacturer extends Model
{
    protected $fillable = ['name', 'logo_path'];

    /** Связь: один производитель — много материалов (внешний ключ manufacturer_id в materials) */
    public function materials(): HasMany
    {
        return $this->hasMany(Material::class, 'manufacturer_id');
    }

    public function getLogoUrlAttribute(): ?string
    {
        $path = $this->logo_path;
        if ($path === null || $path === '') {
            return null;
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return asset($path);
    }
}
