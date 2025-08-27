<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Organisation;
use App\Models\Receiver;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Receiver>
 */
class ReceiverFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),

            'description' => $this->faker->text(),
            'import_id' => $this->faker->optional()->importId(),
        ];
    }
}
