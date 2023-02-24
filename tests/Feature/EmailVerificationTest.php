<?php

namespace Tests\Feature;

use App\Models\User\User;
use App\Models\User\UserConfiguration;
use App\Models\User\UserGoal;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_can_be_verified()
    {
        $this->seed();

        $user = User::factory()->create(['id' => 1, 'email_verified_at' => null]);

        UserConfiguration::createDefault(1);

        UserGoal::createDefault(1);

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        Event::assertDispatched(Verified::class);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $response->assertRedirect('/email-verified');
    }

    public function test_email_is_not_verified_with_invalid_id()
    {
        $this->seed();

        $user1 = User::factory()->create(['id' => 1, 'email_verified_at' => null, 'email' => 'my-email1@test.test']);
        $user2 = User::factory()->create(['id' => 2, 'email_verified_at' => null, 'email' => 'my-email2@test.test']);

        UserConfiguration::createDefault(1);

        UserGoal::createDefault(1);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user1->id, 'hash' => sha1('dd')]
        );

        $verificationUrl = str_replace('/verify-email/1/', '/verify-email/2/', $verificationUrl);

        $this->actingAs($user1)->get($verificationUrl);

        $this->assertFalse($user1->fresh()->hasVerifiedEmail());

        $this->assertFalse($user2->fresh()->hasVerifiedEmail());
    }

}
