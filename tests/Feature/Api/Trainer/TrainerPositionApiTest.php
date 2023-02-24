<?php

namespace Tests\Feature\Api\Trainer;

use App\Models\Experience\Experience;
use App\Models\Trainer\TrainerPosition;
use App\Models\User\User;
use App\Models\User\UserConfiguration;
use App\Models\User\UserGoal;
use App\Models\User\UserNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainerPositionApiTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void {
        parent::setUp();
        $this->seed();
        $this->user = User::factory()->create(['experience' => 10]);
        UserConfiguration::createDefault($this->user->id);
        UserNotification::createDefault($this->user->id);
        UserGoal::createDefault($this->user->id);
    }

    /**
     * Test if user can complete trainer position without take back.
     */
    public function test_user_can_complete_trainer_position_without_take_back(){
        $this->actingAs($this->user, 'api');

        $randomTrainerPosition = TrainerPosition::inRandomOrder()->first();

        $experienceCount = Experience::all()->count();

        $response = $this->json('POST', '/api/trainer/complete/'.$randomTrainerPosition->uuid, ['takeBack' => false]);

        $response->assertStatus(200);

        $content = json_decode($response->content());

        $this->assertTrue($content->message === 'Position completed successfully.');

        $this->assertTrue($experienceCount+1 === Experience::all()->count());

        $lastExperienceInsert = Experience::latest()->first();

        $this->assertTrue($lastExperienceInsert->experience === 15);

        $userExperience = User::find($this->user->id)->experience;

        $this->assertTrue($userExperience === 25);
    }

    /**
     * Test if user can complete trainer position with take back.
     */
    public function test_user_can_complete_trainer_position_with_take_back(){
        $this->actingAs($this->user, 'api');

        $randomTrainerPosition = TrainerPosition::inRandomOrder()->first();

        $experienceCount = Experience::all()->count();

        $response = $this->json('POST', '/api/trainer/complete/'.$randomTrainerPosition->uuid, ['takeBack' => true]);

        $response->assertStatus(200);

        $content = json_decode($response->content());

        $this->assertTrue($content->message === 'Position completed successfully.');

        $this->assertTrue($experienceCount+1 === Experience::all()->count());

        $lastExperienceInsert = Experience::latest()->first();

        $this->assertTrue($lastExperienceInsert->experience === 10);

        $userExperience = User::find($this->user->id)->experience;

        $this->assertTrue($userExperience === 20);
    }

    /**
     * Test user cannot complete trainer position with invalid input.
     */
    public function test_user_cannot_complete_trainer_position_with_invalid_input(){
        $this->actingAs($this->user, 'api');

        $randomTrainerPosition = TrainerPosition::inRandomOrder()->first();

        $response = $this->json('POST', '/api/trainer/complete/'.$randomTrainerPosition->uuid, []);

        $response->assertStatus(422);
    }

    /**
     * Test user cannot complete trainer position with invalid uuid.
     */
    public function test_user_cannot_complete_trainer_position_with_invalid_uuid(){
        $this->actingAs($this->user, 'api');

        $response = $this->json('POST', '/api/trainer/complete/random', ['takeBack' => false]);

        $response->assertStatus(404);
    }
}
