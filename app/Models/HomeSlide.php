<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class HomeSlide extends Model
{
    protected $fillable = [
        'slot',
        'image_path',
        'alt_text',
    ];

    protected function casts(): array
    {
        return [
            'slot' => 'integer',
        ];
    }

    /**
     * @return Collection<int, self>
     */
    public static function orderedForHome(): Collection
    {
        return static::query()->orderBy('slot')->get();
    }

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
