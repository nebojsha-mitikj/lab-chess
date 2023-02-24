<?php

namespace Tests\Feature\Api\Setting;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if user can change password.
     * @return void
     */
    public function test_user_can_change_password()
    {
        $this->seed();

        $user = User::factory()->create(['password' => Hash::make('password123')]);

        $this->actingAs($user, 'api');

        $validInput = [
            ['current_password' => 'password123', 'new_password' => 'My new password 123'],
        ];

        foreach($validInput as $input)
            $this->json('PUT', '/api/settings/updatePassword', $input)->assertStatus(200);
    }

    /**
     * Test if user must be authorized to change password using the API.
     * @return void
     */
    public function test_user_must_be_authorized_to_change_password_using_api()
    {
        $response = $this->json('PUT', '/api/settings/updatePassword',
            ['current_password' => 'password123', 'new_password' => 'My new password 123']
        );
        $response->assertStatus(401);
    }

    /**
     * Test update password api with invalid fields
     * @return void
     */
    public function test_update_password_api_with_invalid_fields()
    {
        $this->seed();
        $user = User::factory()->create(['password' => Hash::make('password123')]);

        $this->actingAs($user, 'api');

        $invalidInput = [
            ['current_password' => 'password123', 'new_password' => 1],
            ['current_password' => 'password123'],
            ['new_password' => 1],
            []
        ];

        foreach($invalidInput as $input)
            $this->json('PUT', '/api/settings/updatePassword', $input)->assertStatus(422);
    }

    /**
     * Test update password api with invalid current password.
     * @return void
     */
    public function test_update_password_api_with_invalid_current_password()
    {
        $this->seed();
        $user = User::factory()->create(['password' => Hash::make('password123')]);

        $this->actingAs($user, 'api');

        $invalidInput = [
            ['current_password' => '123password', 'new_password' => 'this should be my new pass 123'],
        ];

        foreach($invalidInput as $input)
            $this->json('PUT', '/api/settings/updatePassword', $input)->assertStatus(422);
    }

}
