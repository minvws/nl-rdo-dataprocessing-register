<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Models\UserLoginToken;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserLoginToken>
 */
class UserLoginTokenFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'token' => $this->faker->unique()->uuid(),
            'user_id' => User::factory(),
            'expires_at' => $this->faker->dateTimeBetween(),
        ];
    }
}
