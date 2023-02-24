<?php

namespace Tests\Feature\Web\Page;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_screen_can_be_rendered()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
