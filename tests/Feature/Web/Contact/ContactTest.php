<?php

namespace Tests\Feature\Web\Contact;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_page_screen_can_be_rendered()
    {
        $response = $this->get('/support/contact');

        $response->assertStatus(200);
    }
}
