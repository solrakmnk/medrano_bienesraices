<?php

namespace App\Livewire;

use App\Models\Property;
use App\Services\PropertySearch;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class PropertyCatalog extends Component
{
    use WithPagination;

    #[Url]
    public string $operation = '';

    #[Url]
    public string $zone = '';

    #[Url]
    public string $min = '';

    #[Url]
    public string $max = '';

    #[Url]
    public string $bedrooms = '';

    #[Url]
    public string $bathrooms = '';

    #[Url]
    public bool $onlyFavorites = false;

    #[Url]
    public bool $mapView = false;

    public array $favoriteIds = [];

    public function updated(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset('operation', 'zone', 'min', 'max', 'bedrooms', 'bathrooms');
        $this->resetPage();
    }

    public function render(): View
    {
        $favoriteIds = array_slice(array_values(array_filter($this->favoriteIds, fn ($id) => is_int($id) && $id > 0)), 0, 100);
        $query = app(PropertySearch::class)->query($this->only('operation', 'zone', 'min', 'max', 'bedrooms', 'bathrooms'));
        if ($this->onlyFavorites) {
            $query->whereIn('id', $favoriteIds);
        }

        return view('livewire.property-catalog', [
            'properties' => (clone $query)->paginate(9),
            'mapProperties' => $this->mapView ? $query->get() : collect(),
            'comparisonProperties' => $this->onlyFavorites ? (clone $query)->get() : collect(),
            'zones' => Property::published()->distinct()->orderBy('neighborhood')->pluck('neighborhood'),
        ]);
    }
}
