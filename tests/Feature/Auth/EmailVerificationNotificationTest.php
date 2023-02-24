<?php

namespace Tests\Feature\Auth;

use App\Models\User\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailVerificationNotificationTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Test if user can access email verification notification API when authenticated.
     */
    public function test_user_can_access_email_verification_notification_api_when_authenticated()
    {
        $this->seed();

        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $response = $this->json('POST', '/email/verification-notification');

        $response->assertStatus(302);

        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    /**
     * Test if user must be authorized to access email verification notification API.
     * @return void
     */
    public function test_user_must_be_authorized_to_access_email_verification_notification_api()
    {
        $response = $this->json('POST', '/email/verification-notification');
        $response->assertStatus(401);
    }

}
