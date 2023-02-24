<?php

namespace Tests\Feature\Api\Course;

use App\Models\Course\Lecture;
use App\Models\User\User;
use App\Models\User\UserConfiguration;
use App\Models\User\UserGoal;
use App\Models\User\UserNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LectureApiTest extends TestCase
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
     * Test if user can complete lecture.
     */
    public function test_user_can_complete_lecture()
    {
        $this->actingAs($this->user, 'api');

        $randomLecture = Lecture::inRandomOrder()->first();

        $response = $this->json('POST', '/api/course/lecture/'.$randomLecture->id);

        $response->assertStatus(200);
    }

    /**
     * Test if user can complete lecture with invalid id.
     */
    public function test_user_can_complete_lecture_with_invalid_id()
    {
        $this->actingAs($this->user, 'api');

        $response = $this->json('POST', '/api/course/lecture/-1');

        $response->assertStatus(404);
    }


    /**
     * Test if unauthenticated user can complete lecture.
     */
    public function test_unauthenticated_user_can_complete_lecture()
    {
        $randomLecture = Lecture::inRandomOrder()->first();

        $response = $this->json('POST', '/api/course/lecture/'.$randomLecture->id);

        $response->assertStatus(401);
    }
}
