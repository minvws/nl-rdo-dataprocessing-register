<?php

declare(strict_types=1);

use App\Enums\Authorization\Permission;
use App\Enums\Snapshot\SnapshotApprovalStatus;
use App\Filament\Resources\SnapshotResource;
use App\Filament\Resources\SnapshotResource\Pages\ViewSnapshot;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\RelatedSnapshotSource;
use App\Models\Responsible;
use App\Models\Snapshot;
use App\Models\SnapshotApproval;
use App\Models\SnapshotApprovalLog;
use App\Models\SnapshotData;
use App\Models\States\Snapshot\Approved;
use App\Models\States\Snapshot\Established;
use App\Models\States\Snapshot\InReview;
use App\Models\States\Snapshot\Obsolete;
use App\Models\Wpg\WpgProcessingRecord;
use Tests\Helpers\Model\OrganisationTestHelper;
use Tests\Helpers\Model\UserTestHelper;

it('loads the snapshot', function (): void {
    $organisation = OrganisationTestHelper::create();
    $snapshot = Snapshot::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ViewSnapshot::class, [
            'record' => $snapshot->getRouteKey(),
        ])
        ->assertSee($snapshot->name);
});

it('loads the view page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $snapshot = Snapshot::factory()
        ->recycle($organisation)
        ->create();
    SnapshotApproval::factory()
        ->create(['snapshot_id' => $snapshot->id]);
    SnapshotApprovalLog::factory()
        ->create(['snapshot_id' => $snapshot->id]);

    $this->asFilamentOrganisationUser($organisation)
        ->get(SnapshotResource::getUrl('view', [
            'record' => $snapshot,
        ]))
        ->assertSuccessful();
});

it('loads the view page if snapshotSource is deleted', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisationWithPermissions($organisation, [
        Permission::SNAPSHOT_VIEW,
    ]);

    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create([
            'deleted_at' => fake()->dateTime(),
        ]);
    $snapshot = Snapshot::factory()
        ->recycle($organisation)
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->create();
    SnapshotApproval::factory()
        ->create(['snapshot_id' => $snapshot->id]);
    SnapshotApprovalLog::factory()
        ->create(['snapshot_id' => $snapshot->id]);

    $this->withFilamentSession($user, $organisation)
        ->get(SnapshotResource::getUrl('view', ['record' => $snapshot]))
        ->assertSuccessful();
});

it('displays the snapshot approval count', function (): void {
    $organisation = OrganisationTestHelper::create();
    $snapshot = Snapshot::factory()
        ->recycle($organisation)
        ->create();
    SnapshotApproval::factory([
        'snapshot_id' => $snapshot->id,
        'status' => SnapshotApprovalStatus::APPROVED,
    ])->create();
    SnapshotApproval::factory([
        'snapshot_id' => $snapshot->id,
        'status' => SnapshotApprovalStatus::UNKNOWN,
    ])->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ViewSnapshot::class, [
            'record' => $snapshot->getRouteKey(),
        ])
        ->assertSee('Ondertekeningen')
        ->assertSee('1 / 2');
});

it('can approve a snapshotApproval', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation);
    $snapshot = Snapshot::factory()
        ->recycle($organisation)
        ->create();
    $snapshotApproval = SnapshotApproval::factory()
        ->create([
            'snapshot_id' => $snapshot->id,
            'assigned_to' => $user->id,
            'status' => SnapshotApprovalStatus::UNKNOWN,
        ]);

    $this->asFilamentUser($user)
        ->createLivewireTestable(ViewSnapshot::class, [
            'record' => $snapshotApproval->snapshot->getRouteKey(),
        ])
        ->mountInfolistAction('snapshot_approval_actions', 'snapshot_approval_approve_action')
        ->callInfolistAction('snapshot_approval_actions', 'snapshot_approval_approve_action', [], ['next' => false]);

    $this->assertDatabaseHas(SnapshotApproval::class, [
        'assigned_to' => $user->id,
        'status' => SnapshotApprovalStatus::APPROVED,
    ]);
});

it('can approve a snapshotApproval and view next', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation);
    $snapshot = Snapshot::factory()
        ->recycle($organisation)
        ->create();
    $snapshotApproval = SnapshotApproval::factory()
        ->for($snapshot)
        ->for($user, 'assignedTo')
        ->create([
            'status' => SnapshotApprovalStatus::UNKNOWN,
        ]);
    $nextSnapshot = Snapshot::factory()
        ->recycle($organisation)
        ->create();
    SnapshotApproval::factory()
        ->for($nextSnapshot)
        ->for($user, 'assignedTo')
        ->create([
            'status' => SnapshotApprovalStatus::UNKNOWN,
        ]);

    $this->asFilamentUser($user)
        ->createLivewireTestable(ViewSnapshot::class, [
            'record' => $snapshotApproval->snapshot->getRouteKey(),
        ])
        ->mountInfolistAction('snapshot_approval_actions', 'snapshot_approval_approve_action')
        ->callInfolistAction('snapshot_approval_actions', 'snapshot_approval_approve_action', [], ['next' => true])
        ->assertRedirect(ViewSnapshot::getUrl(['record' => $nextSnapshot]));

    $this->assertDatabaseHas(SnapshotApproval::class, [
        'assigned_to' => $user->id,
        'status' => SnapshotApprovalStatus::APPROVED,
    ]);
});

it('can decline a snapshotApproval', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation);
    $snapshot = Snapshot::factory()
        ->recycle($organisation)
        ->create();
    $snapshotApproval = SnapshotApproval::factory()
        ->create([
            'snapshot_id' => $snapshot->id,
            'assigned_to' => $user->id,
            'status' => SnapshotApprovalStatus::UNKNOWN,
        ]);

    $this->asFilamentUser($user)
        ->createLivewireTestable(ViewSnapshot::class, [
            'record' => $snapshotApproval->snapshot->getRouteKey(),
        ])
        ->mountInfolistAction('snapshot_approval_actions', 'snapshot_approval_decline_action')
        ->callInfolistAction('snapshot_approval_actions', 'snapshot_approval_decline_action', [], ['next' => false]);

    $this->assertDatabaseHas(SnapshotApproval::class, [
        'assigned_to' => $user->id,
        'status' => SnapshotApprovalStatus::DECLINED,
    ]);
});

it('can decline a snapshotApproval and view next', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation);
    $snapshot = Snapshot::factory()
        ->recycle($organisation)
        ->create();
    $snapshotApproval = SnapshotApproval::factory()
        ->for($snapshot)
        ->for($user, 'assignedTo')
        ->create([
            'status' => SnapshotApprovalStatus::UNKNOWN,
        ]);
    $nextSnapshot = Snapshot::factory()
        ->recycle($organisation)
        ->create();
    SnapshotApproval::factory()
        ->for($nextSnapshot)
        ->for($user, 'assignedTo')
        ->create([
            'status' => SnapshotApprovalStatus::UNKNOWN,
        ]);

    $this->asFilamentUser($user)
        ->createLivewireTestable(ViewSnapshot::class, [
            'record' => $snapshotApproval->snapshot->getRouteKey(),
        ])
        ->mountInfolistAction('snapshot_approval_actions', 'snapshot_approval_decline_action')
        ->callInfolistAction('snapshot_approval_actions', 'snapshot_approval_decline_action', [], ['next' => true])
        ->assertRedirect(ViewSnapshot::getUrl(['record' => $nextSnapshot]));

    $this->assertDatabaseHas(SnapshotApproval::class, [
        'assigned_to' => $user->id,
        'status' => SnapshotApprovalStatus::DECLINED,
    ]);
});

it('can transition to a new state with all approved', function (): void {
    $organisation = OrganisationTestHelper::create();
    $snapshot = Snapshot::factory()
        ->recycle($organisation)
        ->create([
            'state' => InReview::class,
        ]);
    SnapshotApproval::factory()->create([
        'snapshot_id' => $snapshot->id,
        'status' => SnapshotApprovalStatus::APPROVED,
    ]);

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ViewSnapshot::class, [
            'record' => $snapshot->getRouteKey(),
        ])
        ->callAction('snapshot_transition_to_approved');

    $this->assertDatabaseHas(Snapshot::class, [
        'state' => Approved::$name,
    ]);
});

it('can transition to a new state with not all approved', function (): void {
    $organisation = OrganisationTestHelper::create();
    $snapshot = Snapshot::factory()
        ->recycle($organisation)
        ->create([
            'state' => InReview::class,
        ]);
    SnapshotApproval::factory()->create([
        'snapshot_id' => $snapshot->id,
        'status' => SnapshotApprovalStatus::DECLINED,
    ]);
    SnapshotApproval::factory()->create([
        'snapshot_id' => $snapshot->id,
        'status' => SnapshotApprovalStatus::APPROVED,
    ]);

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ViewSnapshot::class, [
            'record' => $snapshot->getRouteKey(),
        ])
        ->callAction('snapshot_transition_to_approved');

    $this->assertDatabaseHas(Snapshot::class, [
        'state' => Approved::$name,
    ]);
});

it('shows the transition button for all states', function (string $currentState, string $expectedState): void {
    $organisation = OrganisationTestHelper::create();
    $snapshot = Snapshot::factory()
        ->recycle($organisation)
        ->create([
            'state' => $currentState,
        ]);

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ViewSnapshot::class, [
            'record' => $snapshot->getRouteKey(),
        ])
        ->assertActionExists(sprintf('snapshot_transition_to_%s', $expectedState));
})->with([
    [InReview::$name, Approved::$name],
    [Approved::$name, Established::$name],
    [InReview::$name, Obsolete::$name],
    [Approved::$name, Obsolete::$name],
    [Established::$name, Obsolete::$name],
]);

it('does not display approval-data if none given', function (): void {
    $organisation = OrganisationTestHelper::create();
    $snapshot = Snapshot::factory()
        ->recycle($organisation)
        ->create([
            'state' => InReview::class,
        ]);

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ViewSnapshot::class, [
            'record' => $snapshot->getRouteKey(),
        ])
        ->assertDontSee(__('snapshot_approval.reviewed_at'));
});

it('can render the page if snapshot has no snapshot-data', function (): void {
    $organisation = OrganisationTestHelper::create();
    $snapshot = Snapshot::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ViewSnapshot::class, [
            'record' => $snapshot->getRouteKey(),
        ])
        ->assertOk();
});

it('can render the page if snapshot-data has no public_markdown', function (): void {
    $organisation = OrganisationTestHelper::create();
    $snapshot = Snapshot::factory()
        ->recycle($organisation)
        ->create();
    SnapshotData::factory()
        ->for($snapshot)
        ->create([
            'public_markdown' => null,
        ]);

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ViewSnapshot::class, [
            'record' => $snapshot->getRouteKey(),
        ])
        ->assertOk();
});

it('can render related entities in public markdown', function (): void {
    $repsonsiblePublicMarkdown = fake()->sentence();
    $avgResponsibleProcessingRecordPublicMarkdown = fake()->sentence();

    $organisation = OrganisationTestHelper::create();
    $responsible = Responsible::factory()
        ->recycle($organisation)
        ->create();
    $responsibleSnapshot = Snapshot::factory()
        ->recycle($organisation)
        ->for($responsible, 'snapshotSource')
        ->create([
            'state' => Established::class,
        ]);
    SnapshotData::factory()
        ->for($responsibleSnapshot)
        ->create([
            'public_markdown' => $repsonsiblePublicMarkdown,
        ]);

    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create();
    $avgResponsibleProcessingRecordSnapshot = Snapshot::factory()
        ->recycle($organisation)
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->create([
            'state' => Established::class,
        ]);
    SnapshotData::factory()
        ->for($avgResponsibleProcessingRecordSnapshot)
        ->create([
            'public_markdown' => sprintf('%s <!--- #App\Models\Responsible# --->', $avgResponsibleProcessingRecordPublicMarkdown),
            'private_markdown' => fake()->markdown(),
        ]);

    RelatedSnapshotSource::factory()
        ->create([
            'snapshot_id' => $avgResponsibleProcessingRecordSnapshot->id,
            'snapshot_source_id' => $responsible->id,
            'snapshot_source_type' => $responsible::class,
        ]);

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ViewSnapshot::class, [
            'record' => $avgResponsibleProcessingRecordSnapshot->getRouteKey(),
        ])
        ->assertSee([$avgResponsibleProcessingRecordPublicMarkdown, $repsonsiblePublicMarkdown]);
});

it('will not render related entities without public markdown', function (): void {
    $avgResponsibleProcessingRecordPublicMarkdown = fake()->sentence();

    $organisation = OrganisationTestHelper::create();
    $responsible = Responsible::factory()
        ->recycle($organisation)
        ->create();
    $responsibleSnapshot = Snapshot::factory()
        ->recycle($organisation)
        ->for($responsible, 'snapshotSource')
        ->create([
            'state' => Established::class,
        ]);
    SnapshotData::factory()
        ->for($responsibleSnapshot)
        ->create([
            'public_markdown' => null,
        ]);

    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create();
    $avgResponsibleProcessingRecordSnapshot = Snapshot::factory()
        ->recycle($organisation)
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->create([
            'state' => Established::class,
        ]);
    SnapshotData::factory()
        ->for($avgResponsibleProcessingRecordSnapshot)
        ->create([
            'public_markdown' => sprintf('%s <!--- #App\Models\Responsible# --->', $avgResponsibleProcessingRecordPublicMarkdown),
        ]);

    RelatedSnapshotSource::factory()
        ->create([
            'snapshot_id' => $avgResponsibleProcessingRecordSnapshot->id,
            'snapshot_source_id' => $responsible->id,
            'snapshot_source_type' => $responsible::class,
        ]);

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ViewSnapshot::class, [
            'record' => $avgResponsibleProcessingRecordSnapshot->getRouteKey(),
        ])
        ->assertSee([$avgResponsibleProcessingRecordPublicMarkdown]);
});

it('will not fail if no template is specified for a related entity', function (): void {
    $wpgProcessingRecordPublicMarkdown = fake()->sentence();
    $avgResponsibleProcessingRecordPublicMarkdown = fake()->sentence();

    $organisation = OrganisationTestHelper::create();
    $wpgProcessingRecord = WpgProcessingRecord::factory() // assuming this will never be related to an avg-record, I can abuse it here
        ->recycle($organisation)
        ->create();
    $wpgProcessingRecordSnapshot = Snapshot::factory()
        ->recycle($organisation)
        ->for($wpgProcessingRecord, 'snapshotSource')
        ->create([
            'state' => Established::class,
        ]);
    SnapshotData::factory()
        ->for($wpgProcessingRecordSnapshot)
        ->create([
            'public_markdown' => $wpgProcessingRecordPublicMarkdown,
        ]);

    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create();
    $avgResponsibleProcessingRecordSnapshot = Snapshot::factory()
        ->recycle($organisation)
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->create([
            'state' => Established::class,
        ]);
    SnapshotData::factory()
        ->for($avgResponsibleProcessingRecordSnapshot)
        ->create([
            'public_markdown' => sprintf(
                '%s <!--- #App\Models\Wpg\WpgProcessingRecord# --->',
                $avgResponsibleProcessingRecordPublicMarkdown,
            ),
        ]);

    RelatedSnapshotSource::factory()
        ->create([
            'snapshot_id' => $avgResponsibleProcessingRecordSnapshot->id,
            'snapshot_source_id' => $wpgProcessingRecord->id,
            'snapshot_source_type' => $wpgProcessingRecord::class,
        ]);

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ViewSnapshot::class, [
            'record' => $avgResponsibleProcessingRecordSnapshot->getRouteKey(),
        ])
        ->assertSee([$avgResponsibleProcessingRecordPublicMarkdown])
        ->assertDontSee([$wpgProcessingRecordPublicMarkdown]);
});

it('can export to pdf', function (): void {
    $organisation = OrganisationTestHelper::create();
    $snapshot = Snapshot::factory()
        ->recycle($organisation)
        ->withSnapshotData()
        ->create([
            'state' => InReview::class,
        ]);

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ViewSnapshot::class, [
            'record' => $snapshot->getRouteKey(),
        ])
        ->callAction('export_to_pdf')
        ->assertFileDownloaded();
});

it('can not export to pdf is no snapshot-data available', function (): void {
    $organisation = OrganisationTestHelper::create();
    $snapshot = Snapshot::factory()
        ->recycle($organisation)
        ->create([
            'state' => InReview::class,
        ]);

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ViewSnapshot::class, [
            'record' => $snapshot->getRouteKey(),
        ])
        ->assertActionDisabled('export_to_pdf');
});

it('has a next button if available', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation);
    $snapshot = Snapshot::factory()
        ->recycle($organisation)
        ->create([
            'state' => InReview::class,
        ]);
    SnapshotApproval::factory()
        ->for($snapshot)
        ->for($user, 'assignedTo')
        ->create([
            'status' => SnapshotApprovalStatus::UNKNOWN,
        ]);
    $nextSnapshot = Snapshot::factory()
        ->recycle($organisation)
        ->create([
            'state' => InReview::class,
        ]);
    SnapshotApproval::factory()
        ->for($nextSnapshot)
        ->for($user, 'assignedTo')
        ->create([
            'status' => SnapshotApprovalStatus::UNKNOWN,
        ]);

    $this->asFilamentUser($user)
        ->createLivewireTestable(ViewSnapshot::class, [
            'record' => $snapshot->getRouteKey(),
        ])
        ->assertActionEnabled('approve_view_next');
});

it('has no next button if none available', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation);
    $snapshot = Snapshot::factory()
        ->recycle($organisation)
        ->create([
            'state' => InReview::class,
        ]);
    SnapshotApproval::factory()
        ->for($snapshot)
        ->for($user, 'assignedTo')
        ->create([
            'status' => SnapshotApprovalStatus::UNKNOWN,
        ]);

    $this->asFilamentUser($user)
        ->createLivewireTestable(ViewSnapshot::class, [
            'record' => $snapshot->getRouteKey(),
        ])
        ->assertActionDisabled('approve_view_next');
});

it('has no next button if no permission', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisationWithPermissions($organisation, [Permission::SNAPSHOT_VIEW]);
    $snapshot = Snapshot::factory()
        ->recycle($organisation)
        ->create([
            'state' => InReview::class,
        ]);
    SnapshotApproval::factory()
        ->for($snapshot)
        ->for($user, 'assignedTo')
        ->create([
            'status' => SnapshotApprovalStatus::UNKNOWN,
        ]);
    $nextSnapshot = Snapshot::factory()
        ->recycle($organisation)
        ->create([
            'state' => InReview::class,
        ]);
    SnapshotApproval::factory()
        ->for($nextSnapshot)
        ->for($user, 'assignedTo')
        ->create([
            'status' => SnapshotApprovalStatus::UNKNOWN,
        ]);

    $this->withFilamentSession($user, $organisation)
        ->createLivewireTestable(ViewSnapshot::class, [
            'record' => $snapshot->getRouteKey(),
        ])
        ->assertActionDisabled('approve_view_next');
});

it('shows button to view all snapshot approvals', function (): void {
    $organisation = OrganisationTestHelper::create();
    $snapshot = Snapshot::factory()
        ->recycle($organisation)
        ->create([
            'state' => InReview::class,
        ]);

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ViewSnapshot::class, [
            'record' => $snapshot->getRouteKey(),
        ])
        ->assertActionEnabled('approve_view_all');
});

it('does not show button to view all snapshot approvales if no permission', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisationWithPermissions($organisation, [Permission::SNAPSHOT_VIEW]);
    $snapshot = Snapshot::factory()
        ->recycle($organisation)
        ->create([
            'state' => InReview::class,
        ]);

    $this->withFilamentSession($user, $organisation)
        ->createLivewireTestable(ViewSnapshot::class, [
            'record' => $snapshot->getRouteKey(),
        ])
        ->assertActionDisabled('approve_view_all');
});
