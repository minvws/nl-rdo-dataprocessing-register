<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Snapshot\SnapshotApprovalStatus;
use App\Models\Snapshot;
use App\Models\SnapshotApproval;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SnapshotApproval>
 */
class SnapshotApprovalFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /** @var SnapshotApprovalStatus $snapshotApprovalStatus */
        $snapshotApprovalStatus = $this->faker->randomElement(SnapshotApprovalStatus::cases());

        return [
            'snapshot_id' => Snapshot::factory(),
            'requested_by' => $this->faker->optional()->passthrough(User::factory()),
            'assigned_to' => User::factory(),
            'status' => $snapshotApprovalStatus->value,
            'notified_at' => $this->faker->optional()->dateTime(),
        ];
    }
}
