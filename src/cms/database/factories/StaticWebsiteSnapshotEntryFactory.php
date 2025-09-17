<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Snapshot;
use App\Models\StaticWebsiteCheck;
use App\Models\StaticWebsiteSnapshotEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StaticWebsiteSnapshotEntry>
 */
class StaticWebsiteSnapshotEntryFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'last_static_website_check_id' => StaticWebsiteCheck::factory(),
            'snapshot_id' => Snapshot::factory(),
            'url' => $this->faker->url(),
            'start_date' => $this->faker->dateTime(),
            'end_date' => $this->faker->optional()->dateTime(),
        ];
    }
}
