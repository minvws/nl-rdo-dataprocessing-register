<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Livewire\Snapshot\Approvals;
use App\Models\Snapshot;
use App\Models\SnapshotApproval;
use App\Models\User;
use Carbon\CarbonImmutable;

use function Pest\Livewire\livewire;

it('can request a new reviewer', function (): void {
    $this->user->assignOrganisationRole(Role::PRIVACY_OFFICER, $this->organisation);
    $approvalRequestUser = User::factory()
        ->hasAttached($this->organisation)
        ->create();
    $approvalRequestUser->assignOrganisationRole(Role::MANDATE_HOLDER, $this->organisation);

    $snapshot = Snapshot::factory()
        ->recycle($this->organisation)
        ->create();

    livewire(Approvals::class, [
        'snapshot' => $snapshot,
    ])
        ->mountTableAction('snapshot_approval_request_action')
        ->callTableAction('snapshot_approval_request_action', $snapshot, [
            'user_ids' => [$approvalRequestUser->id],
        ])
        ->assertHasNoTableActionErrors();

    $this->assertDatabaseHas(SnapshotApproval::class, [
        'assigned_to' => $approvalRequestUser->id,
    ]);
});

it('can notify a reviewer', function (): void {
    $this->user->assignOrganisationRole(Role::PRIVACY_OFFICER, $this->organisation);
    $approvalRequestUser = User::factory()
        ->hasAttached($this->organisation)
        ->create();
    $approvalRequestUser->assignOrganisationRole(Role::MANDATE_HOLDER, $this->organisation);

    $snapshot = Snapshot::factory()
        ->recycle($this->organisation)
        ->create();
    $snapshotApproval = SnapshotApproval::factory()->create([
        'snapshot_id' => $snapshot->id,
        'assigned_to' => $approvalRequestUser->id,
        'notified_at' => null,
    ]);

    $now = fake()->dateTime();
    CarbonImmutable::setTestNow($now);

    livewire(Approvals::class, [
        'snapshot' => $snapshot,
    ])
        ->mountTableBulkAction('snapshot_approval_notify_bulk_action', [
            $snapshotApproval->id,
        ])
        ->callTableBulkAction('snapshot_approval_notify_bulk_action', [$snapshotApproval], [
            'user_ids' => [$approvalRequestUser->id],
        ])
        ->assertHasNoTableActionErrors()
        ->assertNotified(__('snapshot_approval.notification_sent'));


    $this->assertDatabaseHas(SnapshotApproval::class, [
        'assigned_to' => $approvalRequestUser->id,
        'notified_at' => $now,
    ]);
});

it('can delete a reviewer', function (): void {
    $this->user->assignOrganisationRole(Role::PRIVACY_OFFICER, $this->organisation);
    $approvalRequestUser = User::factory()
        ->hasAttached($this->organisation)
        ->create();
    $approvalRequestUser->assignOrganisationRole(Role::MANDATE_HOLDER, $this->organisation);

    $snapshot = Snapshot::factory()
        ->recycle($this->organisation)
        ->create();
    $snapshotApproval = SnapshotApproval::factory()->create([
        'snapshot_id' => $snapshot->id,
        'assigned_to' => $approvalRequestUser->id,
        'notified_at' => null,
    ]);

    livewire(Approvals::class, [
        'snapshot' => $snapshot,
    ])
        ->mountTableBulkAction('snapshot_approval_notify_bulk_delete', [
            $snapshotApproval->id,
        ])
        ->callTableBulkAction('snapshot_approval_notify_bulk_delete', [$snapshotApproval], [
            'user_ids' => [$approvalRequestUser->id],
        ])
        ->assertHasNoTableActionErrors();

    $this->assertDatabaseMissing(SnapshotApproval::class, [
        'assigned_to' => $approvalRequestUser->id,
    ]);
});
