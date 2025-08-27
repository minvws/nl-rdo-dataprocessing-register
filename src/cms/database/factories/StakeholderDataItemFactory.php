<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Organisation;
use App\Models\StakeholderDataItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StakeholderDataItem>
 */
class StakeholderDataItemFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),
            'import_id' => $this->faker->optional()->importId(),
            'description' => $this->faker->text(),
            'collection_purpose' => $this->faker->text(),
            'retention_period' => $this->faker->text(),
            'is_source_stakeholder' => $this->faker->boolean(),
            'source_description' => $this->faker->text(),
            'is_stakeholder_mandatory' => $this->faker->boolean(),
            'stakeholder_consequences' => $this->faker->text(),
        ];
    }
}
