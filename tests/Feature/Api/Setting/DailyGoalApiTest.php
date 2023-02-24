<?php

namespace Tests\Feature\Api\Setting;

use App\Models\Goal\DailyGoal;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DailyGoalApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if user can update daily goal.
     * @return void
     */
    public function test_user_can_update_daily_goal()
    {
        $this->seed();

        $user = User::factory()->create();

        $dailyGoalId = DailyGoal::first()->id;

        $this->actingAs($user, 'api');

        $response = $this->json('PUT', '/api/settings/updateGoal', ['daily_goal_id' => $dailyGoalId]);

        $response->assertStatus(200);
    }

    /**
     * Test if user must be authorized to update daily goal.
     * @return void
     */
    public function test_must_be_authorized_to_update_daily_goal()
    {
        $response = $this->json('PUT', '/api/settings/updatePiece', ['daily_goal_id' => 1]);

        $response->assertStatus(401);
    }

    /**
     * Test update daily goal api with invalid fields
     * @return void
     */
    public function test_update_daily_goal_invalid_fields_validation()
    {
        $this->seed();
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $invalidInput = [
            ['daily_goal_id' => 'string'],
            []
        ];

        foreach($invalidInput as $input)
            $this->json('PUT', '/api/settings/updatePiece', $input)->assertStatus(422);
    }
}
