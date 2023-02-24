<?php

namespace Tests\Feature\Api\Setting;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if user can update notification settings.
     * @return void
     */
    public function test_user_can_update_notification_settings()
    {
        $this->seed();

        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $validInput = [
            ['field' => 'product_update', 'value' => true],
            ['field' => 'new_follow', 'value' => true],
            ['field' => 'weekly_report', 'value' => true],
            ['field' => 'product_update', 'value' => false],
            ['field' => 'new_follow', 'value' => false],
            ['field' => 'weekly_report', 'value' => false],
        ];

        foreach($validInput as $input)
            $this->json('PUT', '/api/settings/updateNotification', $input)->assertStatus(200);
    }

    /**
     * Test if user must be authorized to update notification settings.
     * @return void
     */
    public function test_must_be_authorized_to_update_notification_settings()
    {
        $response = $this->json('PUT', '/api/settings/updateNotification', ['field' => 'product_update', 'value' => true]);
        $response->assertStatus(401);
    }

    /**
     * Test update notification settings api with invalid fields
     * @return void
     */
    public function test_update_notification_settings_invalid_fields_validation()
    {
        $this->seed();
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $invalidInput = [
            ['field' => 'this is invalid', 'value' => true],
            ['field' => 1, 'value' => true],
            ['field' => 'weekly_report'],
            ['value' => false],
            []
        ];

        foreach($invalidInput as $input)
            $this->json('PUT', '/api/settings/updateNotification', $input)->assertStatus(422);
    }
}
