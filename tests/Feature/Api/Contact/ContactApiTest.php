<?php

namespace Tests\Feature\Api\Contact;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ContactApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if user can contact us.
     * @return void
     */
    public function test_user_can_contact_us()
    {

        $path = base_path('public/test').'/12m5f8TL21.jpg';
        $attachment = new UploadedFile($path, '12m5f8TL21.jpg','image/jpeg', null, true);

        $input = [
            'email' => 'unit@test.feature',
            'subject' => '[Automated-Feature-Test]',
            'message' => 'Contact us works as expected.',
            'input' => $attachment
        ];

        $this->json('POST', '/api/support/contact', $input)->assertStatus(200);
    }


    /**
     * Test contact us with invalid fields.
     * @return void
     */
    public function test_contact_us_with_invalid_fields_validation()
    {
        $invalidInput = [
            [],
            ['email' => 'hello@world.com'],
            ['message' => 'Hello'],
            ['subject' => 'Hii'],
            ['email' => 'hello@world.com', 'message' => 'Hii'],
            ['email' => 'hello@world.com', 'subject' => 'Hii'],
            ['message' => 'hii', 'subject' => 'Hii']
        ];

        foreach($invalidInput as $input)
            $this->json('POST', '/api/support/contact', $input)->assertStatus(422);
    }
}
