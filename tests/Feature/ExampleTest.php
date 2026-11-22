<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * The application root redirects to the category page.
     */
    public function test_the_application_redirects_to_categories(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/categories');
    }
}
