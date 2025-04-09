<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\FgRemark;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FgRemark>
 */
class FgRemarkFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'body' => $this->faker->optional()->text(),
            'fg_remark_relatable_id' => AvgProcessorProcessingRecord::factory(),
            'fg_remark_relatable_type' => AvgProcessorProcessingRecord::class,
        ];
    }
}
