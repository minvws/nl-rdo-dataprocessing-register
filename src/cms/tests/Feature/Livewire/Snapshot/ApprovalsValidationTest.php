<?php

declare(strict_types=1);

use App\Enums\Snapshot\SnapshotApprovalStatus;
use App\Livewire\Snapshot\ApprovalsValidation;
use App\Models\Snapshot;
use App\Models\SnapshotApproval;

use function Pest\Livewire\livewire;

it('can load the table', function (): void {
    $snapshot = Snapshot::factory()
        ->recycle($this->organisation)
        ->create();
    SnapshotApproval::factory()
        ->for($snapshot)
        ->create([
            'status' => fake()->randomElement([SnapshotApprovalStatus::DECLINED, SnapshotApprovalStatus::UNKNOWN]),
        ]);

    livewire(ApprovalsValidation::class, [
        'snapshot' => $snapshot,
    ])
        ->loadTable()
        ->assertCountTableRecords(1);
});
