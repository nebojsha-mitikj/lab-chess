<?php

namespace Tests\Feature\Web\Setting;

use App\Models\User\User;
use App\Models\User\UserConfiguration;
use App\Models\User\UserGoal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountTest extends TestCase
{

    use RefreshDatabase;

    public function test_update_account_screen_can_be_rendered_when_authenticated()
    {
        $this->seed();

        $user = User::factory()->create(['id' => 1]);

        UserConfiguration::createDefault(1);
        
        UserGoal::createDefault(1);

        $this->actingAs($user, 'web');

        $response = $this->get('/settings/account');

        $response->assertStatus(200);
    }

    public function test_update_account_screen_cannot_be_rendered_when_unauthenticated()
    {
        $response = $this->get('/settings/account');

        $response->assertStatus(302);
    }
}
