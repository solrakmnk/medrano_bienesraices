<?php

namespace Tests\Feature;

use App\Livewire\PropertyCatalog;
use App\Models\Property;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FavoritesMapTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_favorites_show_only_saved_published_properties_and_respect_filters(): void
    {
        $saved = Property::factory()->create(['title' => 'Casa guardada', 'neighborhood' => 'Zibatá']);
        $hidden = Property::factory()->create(['title' => 'Casa privada', 'published' => false]);
        Property::factory()->create(['title' => 'Casa no guardada']);

        $component = Livewire::test(PropertyCatalog::class)
            ->set('favoriteIds', [$saved->id, $hidden->id])
            ->set('onlyFavorites', true);

        $component->assertSee('Casa guardada')->assertDontSee('Casa privada')->assertDontSee('Casa no guardada')
            ->set('zone', 'Juriquilla')->assertDontSee('Casa guardada')
            ->call('clearFilters')->assertSee('Casa guardada')->assertSet('onlyFavorites', true);
    }

    public function test_empty_favorites_explain_how_to_save_properties(): void
    {
        Property::factory()->create(['title' => 'Casa sin guardar']);

        $component = Livewire::test(PropertyCatalog::class)->set('onlyFavorites', true);

        $component->assertSee('Guarda propiedades desde el catálogo')->assertDontSee('Casa sin guardar');
    }

    public function test_invalid_saved_identifiers_do_not_expand_the_selection(): void
    {
        Property::factory()->create(['title' => 'Casa protegida']);

        $component = Livewire::test(PropertyCatalog::class)
            ->set('favoriteIds', ['1 OR 1=1', ['id' => 1], -1])
            ->set('onlyFavorites', true);

        $component->assertDontSee('Casa protegida')->assertSee('Guarda propiedades desde el catálogo');
    }

    public function test_map_includes_results_beyond_the_first_list_page_and_excludes_unpublished_properties(): void
    {
        Property::factory()->count(9)->create(['price' => 3000000]);
        Property::factory()->create(['title' => 'Casa en segunda página', 'price' => 4000000]);
        Property::factory()->create(['title' => 'Casa privada en mapa', 'published' => false]);

        $component = Livewire::test(PropertyCatalog::class)->set('mapView', true);

        $component->assertSee('Casa en segunda página')->assertDontSee('Casa privada en mapa')
            ->assertSee('Esquema ilustrativo')->assertSee('Escuela de ejemplo');
    }

    public function test_favorites_map_and_comparison_follow_the_same_selection(): void
    {
        $saved = Property::factory()->create(['title' => 'Casa para comparar']);
        Property::factory()->create(['title' => 'Casa descartada']);

        $component = Livewire::test(PropertyCatalog::class)
            ->set('favoriteIds', [$saved->id])->set('onlyFavorites', true)->set('mapView', true);

        $component->assertSee('Casa para comparar')->assertSee('Lo que importa para elegir')->assertDontSee('Casa descartada');
    }
}
