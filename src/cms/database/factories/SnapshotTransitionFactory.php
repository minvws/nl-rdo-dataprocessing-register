<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Snapshot;
use App\Models\SnapshotTransition;
use App\Models\States\SnapshotState;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SnapshotTransition>
 */
class SnapshotTransitionFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'snapshot_id' => Snapshot::factory(),
            'created_by' => User::factory(),

            'state' => $this->faker->randomElement(SnapshotState::all()),
        ];
    }
}
