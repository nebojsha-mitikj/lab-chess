<?php

namespace Tests\Feature\Api\Trainer;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainerVariantApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if user can read trainer variant data.
     * @return void
     */
    public function test_user_can_read_trainer_variant_data()
    {
        $this->seed();

        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $this->json('GET', '/api/trainer/pawn-endgames/1')->assertStatus(200);
    }

    /**
     * Test if user must be authorized to read trainer variant data.
     * @return void
     */
    public function test_must_be_authorized_to_read_trainer_variant_data()
    {
        $this->json('GET', '/api/trainer/pawn-endgames/1')->assertStatus(401);
    }

    /**
     * Test update notification settings api with invalid fields
     * @return void
     */
    public function test_read_trainer_variant_data_invalid_fields_validation()
    {
        $this->seed();
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $this->json('GET', '/api/trainer/hello-world/1')->assertStatus(404);
    }
}
