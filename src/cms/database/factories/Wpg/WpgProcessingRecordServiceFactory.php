<?php

declare(strict_types=1);

namespace Database\Factories\Wpg;

use App\Models\Organisation;
use App\Models\Wpg\WpgProcessingRecordService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WpgProcessingRecordService>
 */
class WpgProcessingRecordServiceFactory extends Factory
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
