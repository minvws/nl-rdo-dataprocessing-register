<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Filament\Resources\PersonalSnapshotApprovalResource;
use App\Filament\Resources\PersonalSnapshotApprovalResource\Pages\ListPersonalSnapshotApprovalItems;
use App\Models\Snapshot;
use App\Models\SnapshotApproval;
use App\Models\States\Snapshot\Approved;

use function Pest\Livewire\livewire;

it('loads the list items', function (): void {
    $this->user->assignOrganisationRole(Role::MANDATE_HOLDER, $this->organisation);

    $snapshotApprovals = SnapshotApproval::factory()
        ->recycle($this->organisation)
        ->count(5)
        ->create([
            'assigned_to' => $this->user,
            'snapshot_id' => Snapshot::factory(state: [
                'state' => Approved::$name,
            ]),
        ]);

    $snapshots = collect($snapshotApprovals)
        ->map(static function (SnapshotApproval $snapshotApproval): Snapshot {
            return $snapshotApproval->snapshot;
        });

    livewire(ListPersonalSnapshotApprovalItems::class)
        ->assertCanSeeTableRecords($snapshots);
});

it('loads the list page', function (): void {
    $this->user->assignOrganisationRole(Role::MANDATE_HOLDER, $this->organisation);

    $this->get(PersonalSnapshotApprovalResource::getUrl())
        ->assertSuccessful();
});
