<?php

declare(strict_types=1);

namespace Database\Factories\Algorithm;

use App\Models\Algorithm\AlgorithmMetaSchema;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AlgorithmMetaSchema>
 */
class AlgorithmMetaSchemaFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),

            'name' => $this->faker->word(),
            'enabled' => $this->faker->boolean(),
        ];
    }
}
