<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class DeploymentTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_health_endpoint_is_available_without_demo_inventory(): void
    {
        $this->get('/up')->assertOk();
    }

    public function test_https_proxy_generates_secure_catalog_links(): void
    {
        $this->withServerVariables(['REMOTE_ADDR' => '10.0.0.1'])
            ->withHeaders(['X-Forwarded-Proto' => 'https', 'X-Forwarded-Port' => '443'])
            ->get('/')
            ->assertOk()
            ->assertSee('https://localhost/propiedades', false)
            ->assertDontSee('http://localhost/propiedades', false);
    }
}
