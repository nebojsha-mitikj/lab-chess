<?php

namespace Tests\Feature\Web\Page;

use App\Models\User\User;
use App\Models\User\UserConfiguration;
use App\Models\User\UserGoal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfilePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_screen_can_be_rendered_when_authenticated()
    {
        $this->seed();

        $user = User::factory()->create(['id' => 1, 'username' => 'LabChess']);

        UserConfiguration::createDefault(1);

        UserGoal::createDefault(1);

        $this->actingAs($user, 'web');

        $response = $this->get('/profile/LabChess');

        $response->assertStatus(200);
    }


    public function test_non_existing_profile_page_returns_404()
    {
        $this->seed();

        $user = User::factory()->create(['id' => 1, 'username' => 'LabChess']);

        UserConfiguration::createDefault(1);

        UserGoal::createDefault(1);

        $this->actingAs($user, 'web');

        $response = $this->get('/profile/NonExistingUsername');

        $response->assertStatus(404);
    }

    public function test_profile_page_screen_cannot_be_rendered_when_unauthenticated(){

        $this->seed();

        User::factory()->create(['id' => 1, 'username' => 'LabChess']);

        UserConfiguration::createDefault(1);

        UserGoal::createDefault(1);

        $response = $this->get('/profile/LabChess');

        $response->assertStatus(302);
    }
}
