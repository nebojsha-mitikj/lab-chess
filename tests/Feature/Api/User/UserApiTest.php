<?php

namespace Tests\Feature\Api\User;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if user can search for users.
     * @return void
     */
    public function test_can_search_for_users()
    {
        $this->seed();

        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $this->json('GET', '/api/user/search?search=lab')->assertStatus(200);
    }
}
