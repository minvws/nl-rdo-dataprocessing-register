<?php

declare(strict_types=1);

namespace Database\Factories\Avg;

use App\Models\Avg\AvgResponsibleProcessingRecordService;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AvgResponsibleProcessingRecordService>
 */
class AvgResponsibleProcessingRecordServiceFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'organisation_id' => Organisation::factory(),

            'name' => $this->faker->sentence(),
            'enabled' => $this->faker->boolean(),
        ];
    }
}
