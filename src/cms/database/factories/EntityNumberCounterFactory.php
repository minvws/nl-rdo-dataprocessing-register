<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\EntityNumberType;
use App\Models\EntityNumberCounter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EntityNumberCounter>
 */
class EntityNumberCounterFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'type' => $this->faker->randomElement(EntityNumberType::cases()),
            'prefix' => $this->faker->unique()->regexify('[A-Z]{3}'),
            'number' => $this->faker->numberBetween(1000, 9999),
        ];
    }
}
