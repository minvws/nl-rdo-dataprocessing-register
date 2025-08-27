<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ResponsibleLegalEntity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ResponsibleLegalEntity>
 */
class ResponsibleLegalEntityFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
        ];
    }
}
