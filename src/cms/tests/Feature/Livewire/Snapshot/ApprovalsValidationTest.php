<?php

declare(strict_types=1);

use App\Enums\Snapshot\SnapshotApprovalStatus;
use App\Livewire\Snapshot\ApprovalsValidation;
use App\Models\Snapshot;
use App\Models\SnapshotApproval;
use Tests\Helpers\Model\OrganisationTestHelper;

it('can load the table', function (): void {
    $organisation = OrganisationTestHelper::create();
    $snapshot = Snapshot::factory()
        ->recycle($organisation)
        ->create();
    SnapshotApproval::factory()
        ->for($snapshot)
        ->create([
            'status' => fake()->randomElement([SnapshotApprovalStatus::DECLINED, SnapshotApprovalStatus::UNKNOWN]),
        ]);

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ApprovalsValidation::class, [
            'snapshot' => $snapshot,
        ])
        ->loadTable()
        ->assertCountTableRecords(1);
});
