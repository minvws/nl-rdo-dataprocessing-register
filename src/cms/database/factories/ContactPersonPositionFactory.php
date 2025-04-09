<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ContactPersonPosition;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContactPersonPosition>
 */
class ContactPersonPositionFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'organisation_id' => Organisation::factory(),

            'name' => $this->faker->word(),
            'enabled' => $this->faker->boolean(),
        ];
    }
}
