<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Remark;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Remark>
 */
class RemarkFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'body' => $this->faker->optional()->text(),
            'user_id' => User::factory(),
            'remark_relatable_id' => AvgProcessorProcessingRecord::factory(),
            'remark_relatable_type' => AvgProcessorProcessingRecord::class,
        ];
    }
}
