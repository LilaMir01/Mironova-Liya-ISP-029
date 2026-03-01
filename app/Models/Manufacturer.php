<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Manufacturer extends Model
{
    protected $fillable = ['name'];

    /** Связь: один производитель — много материалов (внешний ключ manufacturer_id в materials) */
    public function materials(): HasMany
    {
        return $this->hasMany(Material::class, 'manufacturer_id');
    }
}
