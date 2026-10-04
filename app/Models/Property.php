<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['price' => 'decimal:2', 'bathrooms' => 'integer', 'land_area' => 'decimal:2', 'construction_area' => 'decimal:2', 'garden' => 'boolean', 'furnished' => 'boolean', 'pets_allowed' => 'boolean', 'featured' => 'boolean', 'published' => 'boolean', 'amenities' => 'array', 'images' => 'array'];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getFormattedPriceAttribute(): string
    {
        return '$'.number_format((float) $this->price, 0).' MXN'.($this->operation_type === 'renta' ? ' / mes' : '');
    }
}
