<?php

namespace Tests\Feature\Web\Page;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailVerifiedTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verified_page_screen_can_be_rendered()
    {
        $response = $this->get('/email-verified');

        $response->assertStatus(302);
    }
}
