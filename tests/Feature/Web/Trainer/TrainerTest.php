<?php

namespace Tests\Feature\Web\Trainer;

use App\Models\Title\Medal;
use App\Models\User\User;
use App\Models\User\UserConfiguration;
use App\Models\User\UserGoal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainerTest extends TestCase
{
    use RefreshDatabase;

    public function test_trainer_page_screen_can_be_rendered_when_authenticated()
    {
        $this->seed();

        $user = User::factory()->create(['id' => 1, 'username' => 'LabChess']);

        UserConfiguration::createDefault(1);

        UserGoal::createDefault(1);

        $this->actingAs($user, 'web');

        $response = $this->get('/trainer');

        $response->assertStatus(200);
    }

    public function test_trainer_page_screen_cannot_be_rendered_when_unauthenticated(){

        $this->seed();

        User::factory()->create(['id' => 1, 'username' => 'LabChess']);

        UserConfiguration::createDefault(1);

        UserGoal::createDefault(1);

        $response = $this->get('/trainer');

        $response->assertStatus(302);
    }
}
