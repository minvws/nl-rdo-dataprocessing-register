<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Authorization\Role;
use App\Models\User;
use App\Models\UserGlobalRole;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserGlobalRole>
 */
class UserGlobalRoleFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'role' => $this->faker->randomElement(Role::cases()),
            'user_id' => User::factory(),
        ];
    }
}
