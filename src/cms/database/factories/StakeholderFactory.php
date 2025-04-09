<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Organisation;
use App\Models\Stakeholder;
use App\Models\StakeholderDataItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Stakeholder>
 */
class StakeholderFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'organisation_id' => Organisation::factory(),
            'import_id' => $this->faker->optional()->importId(),

            'description' => $this->faker->text(),

            'biometric' => $this->faker->boolean(),
            'faith_or_belief' => $this->faker->boolean(),
            'genetic' => $this->faker->boolean(),
            'health' => $this->faker->boolean(),
            'political_attitude' => $this->faker->boolean(),
            'race_or_ethnicity' => $this->faker->boolean(),
            'sexual_life' => $this->faker->boolean(),
            'trade_association_membership' => $this->faker->boolean(),
            'criminal_law' => $this->faker->boolean(),
            'special_collected_data_explanation' => $this->faker->text(),
            'citizen_service_numbers' => $this->faker->boolean(),
        ];
    }

    public function withStakeholderDataItems(?int $count = null): self
    {
        return $this->afterCreating(function (Stakeholder $stakeholder) use ($count): void {
            StakeholderDataItem::factory()
                ->hasAttached($stakeholder)
                ->recycle($stakeholder->organisation)
                ->count($count ?? $this->faker->randomDigitNotNull())
                ->create();
        });
    }
}
