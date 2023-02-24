<?php

namespace Database\Factories\User;

use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'username' => $this->faker->userName(),
            'full_name' => $this->faker->name(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'profile_picture_url' => "https://lastfm.freetls.fastly.net/i/u/770x0/ba917e06828bb97225b209fa94c1a32c.jpg",
            'experience' => random_int(0, 60000),
            'biography' => $this->faker->text(500),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
