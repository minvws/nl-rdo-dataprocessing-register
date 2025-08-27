<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\EntityNumberType;
use App\Models\EntityNumber;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EntityNumber>
 */
class EntityNumberFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(EntityNumberType::cases()),
            'number' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{5}'),
        ];
    }
}
