<?php

namespace Tests\Feature\Api\Setting;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProfileApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if user can update profile settings.
     * @return void
     */
    public function test_user_can_update_profile_settings()
    {
        $this->seed();

        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $validInput = [
            ['full_name' => 'Nebojsha Mitikj', 'biography' => 'Hello World', 'social_media_links' => 'https://www.instagram.com/labchess'],
            ['biography' => 'Hello World', 'social_media_links' => 'https://www.instagram.com/labchess'],
            ['full_name' => 'Nebojsha Mitikj', 'social_media_links' => 'https://www.instagram.com/labchess'],
            ['full_name' => 'Nebojsha Mitikj', 'biography' => 'Hello World'],
            [],
        ];

        foreach($validInput as $input)
            $this->json('PUT', '/api/settings/updateProfile', $input)->assertStatus(200);
    }

    /**
     * Test if user must be authorized to update profile settings.
     * @return void
     */
    public function test_must_be_authorized_to_update_profile_settings()
    {
        $response = $this->json('PUT', '/api/settings/updateProfile', [
            'full_name' => 'Nebojsha Mitikj', 'biography' => 'Hello World', 'social_media_links' => 'https://www.instagram.com/labchess'
        ]);
        $response->assertStatus(401);
    }

    /**
     * Test update profile settings api with invalid fields
     * @return void
     */
    public function test_update_profile_settings_invalid_fields_validation()
    {
        $this->seed();
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $invalidInput = [
            ['full_name' => true, 'biography' => 'Hello World', 'social_media_links' => 'https://www.instagram.com/labchess'],
            ['biography' => true],
            ['social_media_links' => true],
        ];

        foreach($invalidInput as $input)
            $this->json('PUT', '/api/settings/updateProfile', $input)->assertStatus(422);
    }

    /**
     * Test if user can update profile picture.
     * @return void
     * @TODO Test if picture is uploaded to s3 and with what user_id. Make sure it does not overwrite user image.
     */
    public function test_user_can_update_profile_picture()
    {
        $this->seed();

        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $path = base_path('public/test').'/12m5f8TL21.jpg';
        $file = new UploadedFile($path, '12m5f8TL21.jpg','image/jpeg', null, true);

        $this->json('POST', '/api/settings/updateProfilePicture',['profile_picture_url'=>$file])
            ->assertStatus(200);
    }

}
