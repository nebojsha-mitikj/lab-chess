<?php

namespace Tests\Feature;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register()
    {
        $this->seed();

        $response = $this->post('/register', [
            'email' => 'test@example.com',
            'password' => 'password',
            'username' => 'Hello World Test',
            'timezone' => 'Europe/Skopje',
            'privacyPolicy' => 'on'
        ]);

        $response->assertStatus(302);
        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
