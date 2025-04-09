<?php

declare(strict_types=1);

use App\Filament\Resources\OrganisationSnapshotApprovalResource\Pages\ListOrganisationSnapshotApprovalItems;
use App\Models\Snapshot;
use App\Models\SnapshotApproval;
use App\Models\States\Snapshot\Approved;

use function Pest\Livewire\livewire;

it('loads the list page', function (): void {
    $snapshotApprovals = SnapshotApproval::factory()
        ->recycle($this->organisation)
        ->count(5)
        ->create([
            'snapshot_id' => Snapshot::factory(state: [
                'state' => Approved::$name,
            ]),
        ]);

    $snapshots = collect($snapshotApprovals)
        ->map(static function (SnapshotApproval $snapshotApproval): Snapshot {
            return $snapshotApproval->snapshot;
        });

    livewire(ListOrganisationSnapshotApprovalItems::class)
        ->assertCanSeeTableRecords($snapshots);
});
