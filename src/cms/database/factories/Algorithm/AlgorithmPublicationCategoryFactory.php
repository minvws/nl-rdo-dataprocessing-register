<?php

declare(strict_types=1);

namespace Database\Factories\Algorithm;

use App\Models\Algorithm\AlgorithmPublicationCategory;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AlgorithmPublicationCategory>
 */
class AlgorithmPublicationCategoryFactory extends Factory
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
