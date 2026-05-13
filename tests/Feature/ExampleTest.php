<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertOk();
    }

    public function test_marketing_pages_return_ok(): void
    {
        foreach (['/about', '/legal', '/contact'] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_legal_privacy_tab_via_query_string(): void
    {
        $response = $this->get('/legal?tab=privacy');

        $response->assertOk();
        $response->assertSee("Children's Privacy", false);
    }

    public function test_legal_terms_tab_is_default_without_query(): void
    {
        $response = $this->get('/legal');

        $response->assertOk();
        $response->assertSee('Acceptance of Terms', false);
    }
}
