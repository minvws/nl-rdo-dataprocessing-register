<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Authorization\Role;
use App\Models\Organisation;
use App\Models\OrganisationUserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrganisationUserRole>
 */
class OrganisationUserRoleFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),
            'role' => $this->faker->randomElement(Role::cases()),
            'user_id' => User::factory(),
        ];
    }
}
