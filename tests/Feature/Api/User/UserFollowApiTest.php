<?php

namespace Tests\Feature\Api\User;

use App\Models\User\User;
use App\Models\User\UserNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserFollowApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if user can read followers for user with username.
     */
    public function test_can_read_followers_for_username()
    {
        $this->seed();

        $user = User::factory()->create(['username' => 'labchess']);

        $this->actingAs($user, 'api');

        $this->json('GET', '/api/user/followers?username=labchess&page=1')->assertStatus(200);
        $this->json('GET', '/api/user/following?username=nonexistingusername&page=1')->assertStatus(404);
    }

    /**
     * Test if user can read following for user with username.
     */
    public function test_can_read_following_for_username()
    {
        $this->seed();

        $user = User::factory()->create(['username' => 'labchess']);

        $this->actingAs($user, 'api');

        $this->json('GET', '/api/user/following?username=labchess&page=1')->assertStatus(200);
        $this->json('GET', '/api/user/following?username=nonexistingusername&page=1')->assertStatus(404);
    }

    /**
     * Test if user can follow someone.
     */
    public function test_user_can_follow_and_unfollow(){
        $this->seed();

        $user01 = User::factory()->create(['id' => 1, 'username' => 'user1']);
        User::factory()->create(['id' => 2, 'username' => 'user2']);

        UserNotification::createDefault(2);

        $this->actingAs($user01, 'api');

        $this->json('POST', '/api/user/follow', ['username' => 'user2'])->assertStatus(200);
        $this->json('POST', '/api/user/follow', ['username' => 'nonexistinguser'])->assertStatus(404);
        $this->json('POST', '/api/user/unfollow', ['username' => 'user2'])->assertStatus(200);
        $this->json('POST', '/api/user/unfollow', ['username' => 'nonexistinguser'])->assertStatus(404);
    }
}
