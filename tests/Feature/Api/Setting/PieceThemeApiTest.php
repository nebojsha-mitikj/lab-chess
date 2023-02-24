<?php

namespace Tests\Feature\Api\Setting;

use App\Models\Piece\PieceTheme;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PieceThemeApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if user can update piece theme.
     * @return void
     */
    public function test_user_can_update_piece_theme()
    {
        $this->seed();

        $user = User::factory()->create();

        $pieceThemeId = PieceTheme::first()->id;

        $this->actingAs($user, 'api');

        $response = $this->json('PUT', '/api/settings/updatePiece', ['piece_theme_id' => $pieceThemeId]);

        $response->assertStatus(200);
    }

    /**
     * Test if user must be authorized to update piece theme.
     * @return void
     */
    public function test_must_be_authorized_to_update_board_theme()
    {
        $response = $this->json('PUT', '/api/settings/updatePiece', ['piece_theme_id' => 1]);

        $response->assertStatus(401);
    }

    /**
     * Test update piece api with invalid fields
     * @return void
     */
    public function test_update_piece_theme_invalid_fields_validation()
    {
        $this->seed();

        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $invalidInput = [
            ['piece_theme_id' => 'string'],
            []
        ];

        foreach($invalidInput as $input)
            $this->json('PUT', '/api/settings/updatePiece', $input)->assertStatus(422);
    }
}
