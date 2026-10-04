<?php

namespace Tests\Feature;

use App\Livewire\AdvisorDemo;
use App\Livewire\PropertyCatalog;
use App\Models\Property;
use App\Services\WhatsApp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RealEstateTest extends TestCase
{
    use RefreshDatabase;

    public function test_pages_render_and_unpublished_property_is_hidden(): void
    {
        $this->seed();
        $this->get('/')->assertOk()->assertSee('Buscas tu lugar');
        $this->get('/propiedades')->assertOk();
        $property = Property::first();
        $this->get('/propiedades/'.$property->slug)->assertOk()->assertSee($property->title);
        $property->update(['published' => false]);
        $this->get('/propiedades/'.$property->slug)->assertNotFound();
    }

    public function test_filters_work_together_and_can_be_reset(): void
    {
        $this->seed();
        Livewire::test(PropertyCatalog::class)->set('operation', 'renta')->assertSee('Luz y espacio para dos')->assertDontSee('Una casa para crecer')->set('zone', 'Álamos')->set('max', '19000')->set('min', '18000')->set('bedrooms', '2')->set('bathrooms', '2')->assertSee('Luz y espacio para dos')->assertDontSee('Una estancia con todo resuelto')->set('max', '100')->assertSee('No encontramos propiedades')->call('clearFilters')->assertSet('operation', '')->assertSee('Una casa para crecer');
    }

    public function test_demo_uses_three_published_family_properties(): void
    {
        $this->seed();
        Livewire::test(AdvisorDemo::class)->set('message', 'Somos una familia que busca una casa con jardín.')->call('suggest')->assertHasNoErrors()->assertSee('Una casa para crecer')->assertSee('Jardín, sobremesas y nuevos recuerdos')->assertSee('Una nueva etapa en El Refugio');
    }

    public function test_whatsapp_requires_configuration_and_encodes_property_message(): void
    {
        config(['advisor.whatsapp' => null]);
        $this->assertNull(app(WhatsApp::class)->url());
        config(['advisor.whatsapp' => '52 442 123 4567']);
        $p = Property::factory()->make(['title' => 'Casa Jardín & Sol']);
        $url = app(WhatsApp::class)->url($p);
        $this->assertStringStartsWith('https://wa.me/524421234567?text=', $url);
        $this->assertStringContainsString('Casa Jardín & Sol', rawurldecode($url));
    }
}
