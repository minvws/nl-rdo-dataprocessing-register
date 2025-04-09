<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Processor;
use App\Models\RelatedSnapshotSource;
use App\Models\Snapshot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RelatedSnapshotSource>
 */
class RelatedSnapshotSourceFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'snapshot_id' => Snapshot::factory(),
            'snapshot_source_id' => Processor::factory(),
            'snapshot_source_type' => Processor::class,
        ];
    }
}
