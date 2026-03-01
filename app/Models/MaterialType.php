<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaterialType extends Model
{
    protected $fillable = ['name'];

    /** Связь: один тип — много материалов (внешний ключ material_type_id в materials) */
    public function materials(): HasMany
    {
        return $this->hasMany(Material::class, 'material_type_id');
    }
}
