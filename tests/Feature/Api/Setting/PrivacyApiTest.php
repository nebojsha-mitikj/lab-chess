<?php

namespace Tests\Feature\Api\Setting;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrivacyApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if user can update privacy settings.
     * @return void
     */
    public function test_user_can_update_privacy_settings()
    {
        $this->seed();

        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $response = $this->json('PUT', '/api/settings/updatePrivacy', ['public_profile' => true]);

        $response->assertStatus(200);
    }

    /**
     * Test if user must be authorized to update privacy settings.
     * @return void
     */
    public function test_must_be_authorized_to_update_privacy_settings()
    {
        $response = $this->json('PUT', '/api/settings/updatePrivacy', ['public_profile' => true]);

        $response->assertStatus(401);
    }

    /**
     * Test update privacy settings api with invalid fields
     * @return void
     */
    public function test_update_privacy_settings_invalid_fields_validation()
    {
        $this->seed();
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $invalidInput = [
            ['public_profile' => 'string'],
            []
        ];

        foreach($invalidInput as $input)
            $this->json('PUT', '/api/settings/updatePrivacy', $input)->assertStatus(422);
    }

}
