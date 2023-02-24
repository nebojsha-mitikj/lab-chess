<?php

namespace Tests\Feature\Api\Setting;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AccountApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if user can update account settings.
     * @return void
     */
    public function test_user_can_update_account_settings()
    {
        $this->seed();

        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $path = base_path('public/test').'/12m5f8TL21.jpg';
        $file = new UploadedFile($path, '12m5f8TL21.jpg','image/jpeg', null, true);

        $validInput = [
            ['username' => 'hello', 'email' => 'new@email.com', 'sound_effects' => true, 'animation' => true],
            ['username'=>'hello','email'=>'new@email.com','sound_effects'=>true,'animation'=>true,'profile_picture_url'=>$file]
        ];

        foreach($validInput as $input)
            $this->json('POST', '/api/settings/updateAccount', $input)->assertStatus(200);
    }

    /**
     * Test if user must be authorized to update account settings.
     * @return void
     */
    public function test_user_must_be_authorized_to_update_account_settings()
    {
        $response = $this->json('POST', '/api/settings/updateAccount',
            ['username' => 'hello', 'email' => 'new@email.com', 'sound_effects' => true, 'animation' => true]
        );
        $response->assertStatus(401);
    }

    /**
     * Test update account settings api with invalid fields
     * @return void
     */
    public function test_update_account_settings_invalid_fields_validation()
    {
        $this->seed();
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $invalidInput = [
            ['username' => 'hello', 'email' => 'new@email.com', 'sound_effects' => true, 'animation' => 'awd'],
            ['username' => true, 'email' => 'new@email.com', 'sound_effects' => true, 'animation' => true],
            ['username' => 'hello', 'email' => 'invalid email', 'sound_effects' => true, 'animation' => true],
            ['username' => 'hello', 'email' => 'new@email.com', 'sound_effects' => 'String', 'animation' => true],
            ['username' => 'hello', 'email' => 'new@email.com', 'sound_effects' => true],
            ['username' => 'hello', 'email' => 'new@email.com','animation' => true],
            ['username' => 'hello', 'sound_effects' => true, 'animation' => true],
            ['email' => 'new@email.com', 'sound_effects' => true, 'animation' => true]
        ];

        foreach($invalidInput as $input)
            $this->json('POST', '/api/settings/updateAccount', $input)->assertStatus(422);
    }

}
