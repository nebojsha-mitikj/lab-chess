<?php

namespace Tests\Feature\Web\Course;

use App\Models\User\User;
use App\Models\User\UserConfiguration;
use App\Models\User\UserGoal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LectureTest extends TestCase
{
    use RefreshDatabase;

    public function test_lecture_page_screen_can_be_rendered_when_authenticated()
    {
        $this->seed();

        $user = User::factory()->create(['id' => 1]);

        UserConfiguration::createDefault(1);

        UserGoal::createDefault(1);

        $this->actingAs($user, 'web');

        $response = $this->get('/courses/chess-training-I/1.1');

        $response->assertStatus(200);
    }

    public function test_non_existing_lecture_page_screen_returns_302()
    {
        $this->seed();

        $user = User::factory()->create(['id' => 1]);

        UserConfiguration::createDefault(1);

        UserGoal::createDefault(1);

        $this->actingAs($user, 'web');

        $this->get('/courses/chess-training-I/1.1.1.1.1.1.1')->assertStatus(302);
        $this->get('/courses/non-existing-course/1.1')->assertStatus(302);
    }

    public function test_lecture_page_screen_cannot_be_rendered_when_unauthenticated(){

        $this->seed();

        User::factory()->create(['id' => 1]);

        UserConfiguration::createDefault(1);

        UserGoal::createDefault(1);

        $response = $this->get('/courses/chess-training-I/1.1');

        $response->assertStatus(302);
    }
}
