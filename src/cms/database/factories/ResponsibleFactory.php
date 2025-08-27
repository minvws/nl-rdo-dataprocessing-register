<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Address;
use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Organisation;
use App\Models\Responsible;
use App\Models\Wpg\WpgProcessingRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Responsible>
 */
class ResponsibleFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),

            'name' => $this->faker->name(),
            'import_id' => $this->faker->optional()->importId(),
        ];
    }

    public function withAddress(): self
    {
        return $this->afterCreating(static function (Responsible $responsible): void {
            Address::factory()
                ->for($responsible, 'addressable')
                ->create([
                    'addressable_id' => $responsible->id,
                    'addressable_type' => Responsible::class,
                ]);
        });
    }

    public function withAvgProcessorProcessingRecord(): self
    {
        return $this->afterCreating(static function (Responsible $responsible): void {
            AvgProcessorProcessingRecord::factory()
                ->hasAttached($responsible)
                ->recycle($responsible->organisation)
                ->create();
        });
    }

    public function withAvgResponsibleProcessingRecord(): self
    {
        return $this->afterCreating(static function (Responsible $responsible): void {
            AvgResponsibleProcessingRecord::factory()
                ->hasAttached($responsible)
                ->recycle($responsible->organisation)
                ->create();
        });
    }

    public function withWpgProcessingRecord(): self
    {
        return $this->afterCreating(static function (Responsible $responsible): void {
            WpgProcessingRecord::factory()
                ->hasAttached($responsible)
                ->recycle($responsible->organisation)
                ->create();
        });
    }
}
