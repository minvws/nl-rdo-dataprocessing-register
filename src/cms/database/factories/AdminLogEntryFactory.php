<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\AdminLogEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AdminLogEntry>
 */
class AdminLogEntryFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'message' => $this->faker->sentence(),
            'context' => [
                $this->faker->word() => $this->faker->sentence(),
                $this->faker->randomDigit() => $this->faker->sentence(),
            ],
        ];
    }
}
