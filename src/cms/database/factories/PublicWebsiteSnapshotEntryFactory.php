<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PublicWebsiteCheck;
use App\Models\PublicWebsiteSnapshotEntry;
use App\Models\Snapshot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PublicWebsiteSnapshotEntry>
 */
class PublicWebsiteSnapshotEntryFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'last_public_website_check_id' => PublicWebsiteCheck::factory(),
            'snapshot_id' => Snapshot::factory(),
            'url' => $this->faker->url(),
            'start_date' => $this->faker->dateTime(),
            'end_date' => $this->faker->optional()->dateTime(),
        ];
    }
}
