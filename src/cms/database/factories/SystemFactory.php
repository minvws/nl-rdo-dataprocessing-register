<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Organisation;
use App\Models\System;
use App\Models\Wpg\WpgProcessingRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<System>
 */
class SystemFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'organisation_id' => Organisation::factory(),

            'description' => $this->faker->text(),
            'import_id' => $this->faker->optional()->importId(),
        ];
    }

    public function withAvgProcessorProcessingRecord(): self
    {
        return $this->afterCreating(static function (System $system): void {
            AvgProcessorProcessingRecord::factory()
                ->hasAttached($system)
                ->recycle($system->organisation)
                ->create();
        });
    }

    public function withAvgResponsibleProcessingRecord(): self
    {
        return $this->afterCreating(static function (System $system): void {
            AvgResponsibleProcessingRecord::factory()
                ->hasAttached($system)
                ->recycle($system->organisation)
                ->create();
        });
    }

    public function withWpgProcessingRecord(): self
    {
        return $this->afterCreating(static function (System $system): void {
            WpgProcessingRecord::factory()
                ->hasAttached($system)
                ->recycle($system->organisation)
                ->create();
        });
    }
}
