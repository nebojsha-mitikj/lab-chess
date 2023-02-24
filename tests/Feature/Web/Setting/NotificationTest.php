<?php

namespace Tests\Feature\Web\Setting;

use App\Models\User\User;
use App\Models\User\UserConfiguration;
use App\Models\User\UserGoal;
use App\Models\User\UserNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_notification_screen_can_be_rendered_when_authenticated()
    {
        $this->seed();

        $user = User::factory()->create(['id' => 1]);

        UserConfiguration::createDefault(1);

        UserGoal::createDefault(1);

        UserNotification::createDefault(1);

        $this->actingAs($user, 'web');

        $response = $this->get('/settings/notifications');

        $response->assertStatus(200);
    }

    public function test_update_notification_screen_cannot_be_rendered_when_unauthenticated()
    {
        $response = $this->get('/settings/notifications');

        $response->assertStatus(302);
    }
}
