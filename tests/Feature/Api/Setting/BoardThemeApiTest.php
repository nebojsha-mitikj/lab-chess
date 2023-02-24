<?php

namespace Tests\Feature\Api\Setting;

use App\Models\Borad\BoardTheme;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BoardThemeApiTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Test if user can update board theme.
     * @return void
     */
    public function test_user_can_update_board_theme()
    {
        $this->seed();

        $user = User::factory()->create();

        $boardId = BoardTheme::first()->id;

        $this->actingAs($user, 'api');

        $response = $this->json('PUT', '/api/settings/updateBoard', ['board_theme_id' => $boardId]);

        $response->assertStatus(200);
    }

    /**
     * Test if user must be authorized to update board theme.
     * @return void
     */
    public function test_must_be_authorized_to_update_board_theme()
    {
        $response = $this->json('PUT', '/api/settings/updateBoard', ['board_theme_id' => 1]);

        $response->assertStatus(401);
    }

    /**
     * Test update board api with invalid fields
     * @return void
     */
    public function test_update_board_theme_invalid_fields_validation()
    {
        $this->seed();
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $invalidInput = [
            ['board_theme_id' => 'string'],
            []
        ];

        foreach($invalidInput as $input)
            $this->json('PUT', '/api/settings/updateBoard', $input)->assertStatus(422);
    }

}
