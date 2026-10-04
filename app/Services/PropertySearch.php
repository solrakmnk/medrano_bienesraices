<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Database\Eloquent\Builder;

class PropertySearch
{
    public function query(array $filters = []): Builder
    {
        return Property::published()->when($filters['operation'] ?? '', fn ($q, $v) => $q->where('operation_type', $v))
            ->when($filters['zone'] ?? '', fn ($q, $v) => $q->where('neighborhood', $v))
            ->when($filters['min'] ?? '', fn ($q, $v) => $q->where('price', '>=', max(0, (float) $v)))
            ->when($filters['max'] ?? '', fn ($q, $v) => $q->where('price', '<=', max(0, (float) $v)))
            ->when($filters['bedrooms'] ?? '', fn ($q, $v) => $q->where('bedrooms', '>=', (int) $v))
            ->when($filters['bathrooms'] ?? '', fn ($q, $v) => $q->where('bathrooms', '>=', (int) $v))
            ->orderByDesc('featured')->orderBy('price');
    }
}
