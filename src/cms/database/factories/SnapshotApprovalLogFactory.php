<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Snapshot\SnapshotApprovalLogMessageType;
use App\Enums\Snapshot\SnapshotApprovalStatus;
use App\Models\Snapshot;
use App\Models\SnapshotApprovalLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

use function sprintf;

/**
 * @extends Factory<SnapshotApprovalLog>
 */
class SnapshotApprovalLogFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $assignedTo = User::factory()->create();

        return [
            'snapshot_id' => Snapshot::factory(),
            'user_id' => User::factory(),
            'message' => [
                'type' => $this->faker->randomElement(SnapshotApprovalLogMessageType::cases()),
                'assigned_to' => sprintf('%s (%s)', $assignedTo->name, $assignedTo->email),
                'status' => $this->faker->randomElement(SnapshotApprovalStatus::cases()),
                'notes' => $this->faker->optional()->sentence(),
            ],
        ];
    }
}
