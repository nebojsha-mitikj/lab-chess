<?php

namespace Tests\Feature\Web\Trainer;

use App\Models\User\User;
use App\Models\User\UserConfiguration;
use App\Models\User\UserGoal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainerVariantTest extends TestCase
{
    use RefreshDatabase;

    public function test_trainer_variant_page_screen_can_be_rendered_when_authenticated()
    {
        $this->seed();

        $user = User::factory()->create(['id' => 1]);

        UserConfiguration::createDefault(1);

        UserGoal::createDefault(1);

        $this->actingAs($user, 'web');

        $response = $this->get('/trainer/pawn-endgames/1');

        $response->assertStatus(200);
    }

    public function test_non_existing_trainer_variant_page_screen_returns_404()
    {
        $this->seed();

        $user = User::factory()->create(['id' => 1]);

        UserConfiguration::createDefault(1);

        UserGoal::createDefault(1);

        $this->actingAs($user, 'web');

        $this->get('/trainer/pawn-endgames/99999')->assertStatus(404);
        $this->get('/trainer/non-existing-code/1')->assertStatus(404);
    }

    public function test_trainer_variant_page_screen_cannot_be_rendered_when_unauthenticated(){

        $this->seed();

        User::factory()->create(['id' => 1]);

        UserConfiguration::createDefault(1);

        UserGoal::createDefault(1);

        $response = $this->get('/trainer/pawn-endgames/1');

        $response->assertStatus(302);
    }
}
