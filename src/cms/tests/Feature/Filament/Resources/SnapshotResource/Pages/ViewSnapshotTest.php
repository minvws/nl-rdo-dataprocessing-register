<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
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

use function Pest\Livewire\livewire;

it('loads the snapshot', function (): void {
    $snapshot = Snapshot::factory()
        ->recycle($this->organisation)
        ->create();

    livewire(ViewSnapshot::class, [
        'record' => $snapshot->getRouteKey(),
    ])->assertSee($snapshot->name);
});

it('loads the view page', function (): void {
    $this->user->assignOrganisationRole(Role::MANDATE_HOLDER, $this->organisation);

    $snapshot = Snapshot::factory()
        ->recycle($this->organisation)
        ->create();
    SnapshotApproval::factory()
        ->create(['snapshot_id' => $snapshot->id]);
    SnapshotApprovalLog::factory()
        ->create(['snapshot_id' => $snapshot->id]);

    $this->get(SnapshotResource::getUrl('view', ['record' => $snapshot]))
        ->assertSuccessful();
});

it('displays the snapshot approval count', function (): void {
    $this->user->assignOrganisationRole(Role::MANDATE_HOLDER, $this->organisation);

    $snapshot = Snapshot::factory()
        ->recycle($this->organisation)
        ->create();
    SnapshotApproval::factory([
        'snapshot_id' => $snapshot->id,
        'status' => SnapshotApprovalStatus::APPROVED,
    ])->create();
    SnapshotApproval::factory([
        'snapshot_id' => $snapshot->id,
        'status' => SnapshotApprovalStatus::UNKNOWN,
    ])->create();

    livewire(ViewSnapshot::class, [
        'record' => $snapshot->getRouteKey(),
    ])
        ->assertSee('Ondertekeningen')
        ->assertSee('1 / 2');
});

it('can approve a snapshotApproval', function (): void {
    $this->user->assignOrganisationRole(Role::MANDATE_HOLDER, $this->organisation);

    $snapshot = Snapshot::factory()
        ->recycle($this->organisation)
        ->create();
    $snapshotApproval = SnapshotApproval::factory()
        ->create([
            'snapshot_id' => $snapshot->id,
            'assigned_to' => $this->user->id,
            'status' => SnapshotApprovalStatus::UNKNOWN,
        ]);

    livewire(ViewSnapshot::class, [
        'record' => $snapshotApproval->snapshot->getRouteKey(),
    ])
        ->mountInfolistAction('snapshot_approval_actions', 'snapshot_approval_approve_action')
        ->callInfolistAction('snapshot_approval_actions', 'snapshot_approval_approve_action');

    $this->assertDatabaseHas(SnapshotApproval::class, [
        'assigned_to' => $this->user->id,
        'status' => SnapshotApprovalStatus::APPROVED,
    ]);
});

it('can decline a snapshotApproval', function (): void {
    $this->user->assignOrganisationRole(Role::MANDATE_HOLDER, $this->organisation);

    $snapshot = Snapshot::factory()
        ->recycle($this->organisation)
        ->create();
    $snapshotApproval = SnapshotApproval::factory()
        ->create([
            'snapshot_id' => $snapshot->id,
            'assigned_to' => $this->user->id,
            'status' => SnapshotApprovalStatus::UNKNOWN,
        ]);

    livewire(ViewSnapshot::class, [
        'record' => $snapshotApproval->snapshot->getRouteKey(),
    ])
        ->mountInfolistAction('snapshot_approval_actions', 'snapshot_approval_decline_action')
        ->callInfolistAction('snapshot_approval_actions', 'snapshot_approval_decline_action');

    $this->assertDatabaseHas(SnapshotApproval::class, [
        'assigned_to' => $this->user->id,
        'status' => SnapshotApprovalStatus::DECLINED,
    ]);
});

it('can transition to a new state with all approved', function (): void {
    $this->user->assignOrganisationRole(Role::PRIVACY_OFFICER, $this->organisation);

    $snapshot = Snapshot::factory()
        ->recycle($this->organisation)
        ->create([
            'state' => InReview::class,
        ]);
    SnapshotApproval::factory()->create([
        'snapshot_id' => $snapshot->id,
        'status' => SnapshotApprovalStatus::APPROVED,
    ]);

    livewire(ViewSnapshot::class, [
        'record' => $snapshot->getRouteKey(),
    ])
        ->callAction('snapshot_transition_to_approved');

    $this->assertDatabaseHas(Snapshot::class, [
        'state' => Approved::$name,
    ]);
});

it('can transition to a new state with not all approved', function (): void {
    $this->user->assignOrganisationRole(Role::PRIVACY_OFFICER, $this->organisation);

    $snapshot = Snapshot::factory()
        ->recycle($this->organisation)
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

    livewire(ViewSnapshot::class, [
        'record' => $snapshot->getRouteKey(),
    ])
        ->callAction('snapshot_transition_to_approved');

    $this->assertDatabaseHas(Snapshot::class, [
        'state' => Approved::$name,
    ]);
});

it('shows the transition button for all states', function (string $currentState, string $expectedState): void {
    $snapshot = Snapshot::factory()
        ->recycle($this->organisation)
        ->create([
            'state' => $currentState,
        ]);

    livewire(ViewSnapshot::class, [
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
    $this->user->assignOrganisationRole(Role::MANDATE_HOLDER, $this->organisation);

    $snapshot = Snapshot::factory()
        ->recycle($this->organisation)
        ->create([
            'state' => InReview::class,
        ]);

    livewire(ViewSnapshot::class, [
        'record' => $snapshot->getRouteKey(),
    ])
        ->assertDontSee(__('snapshot_approval.reviewed_at'));
});

it('can render the page if snapshot has no snapshot-data', function (): void {
    $this->user->assignOrganisationRole(Role::MANDATE_HOLDER, $this->organisation);

    $snapshot = Snapshot::factory()
        ->recycle($this->organisation)
        ->create();

    livewire(ViewSnapshot::class, [
        'record' => $snapshot->getRouteKey(),
    ])
        ->assertOk();
});

it('can render the page if snapshot-data has no public_markdown', function (): void {
    $this->user->assignOrganisationRole(Role::MANDATE_HOLDER, $this->organisation);

    $snapshot = Snapshot::factory()
        ->recycle($this->organisation)
        ->create();
    SnapshotData::factory()
        ->for($snapshot)
        ->create([
            'public_markdown' => null,
        ]);

    livewire(ViewSnapshot::class, [
        'record' => $snapshot->getRouteKey(),
    ])
        ->assertOk();
});

it('can render related entities in public markdown', function (): void {
    $this->user->assignOrganisationRole(Role::MANDATE_HOLDER, $this->organisation);

    $repsonsiblePublicMarkdown = fake()->sentence();
    $avgResponsibleProcessingRecordPublicMarkdown = fake()->sentence();

    $responsible = Responsible::factory()
        ->recycle($this->organisation)
        ->create();
    $responsibleSnapshot = Snapshot::factory()
        ->recycle($this->organisation)
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
        ->recycle($this->organisation)
        ->create();
    $avgResponsibleProcessingRecordSnapshot = Snapshot::factory()
        ->recycle($this->organisation)
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

    livewire(ViewSnapshot::class, [
        'record' => $avgResponsibleProcessingRecordSnapshot->getRouteKey(),
    ])
        ->assertSee([$avgResponsibleProcessingRecordPublicMarkdown, $repsonsiblePublicMarkdown]);
});

it('will not render related entities without public markdown', function (): void {
    $this->user->assignOrganisationRole(Role::MANDATE_HOLDER, $this->organisation);

    $avgResponsibleProcessingRecordPublicMarkdown = fake()->sentence();

    $responsible = Responsible::factory()
        ->recycle($this->organisation)
        ->create();
    $responsibleSnapshot = Snapshot::factory()
        ->recycle($this->organisation)
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
        ->recycle($this->organisation)
        ->create();
    $avgResponsibleProcessingRecordSnapshot = Snapshot::factory()
        ->recycle($this->organisation)
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

    livewire(ViewSnapshot::class, [
        'record' => $avgResponsibleProcessingRecordSnapshot->getRouteKey(),
    ])
        ->assertSee([$avgResponsibleProcessingRecordPublicMarkdown]);
});

it('will not fail if no template is specified for a related entity', function (): void {
    $this->user->assignOrganisationRole(Role::MANDATE_HOLDER, $this->organisation);

    $wpgProcessingRecordPublicMarkdown = fake()->sentence();
    $avgResponsibleProcessingRecordPublicMarkdown = fake()->sentence();

    $wpgProcessingRecord = WpgProcessingRecord::factory() // assuming this will never be related to an avg-record, I can abuse it here
        ->recycle($this->organisation)
        ->create();
    $wpgProcessingRecordSnapshot = Snapshot::factory()
        ->recycle($this->organisation)
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
        ->recycle($this->organisation)
        ->create();
    $avgResponsibleProcessingRecordSnapshot = Snapshot::factory()
        ->recycle($this->organisation)
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

    livewire(ViewSnapshot::class, [
        'record' => $avgResponsibleProcessingRecordSnapshot->getRouteKey(),
    ])
        ->assertSee([$avgResponsibleProcessingRecordPublicMarkdown])
        ->assertDontSee([$wpgProcessingRecordPublicMarkdown]);
});

it('can export to pdf', function (): void {
    $this->user->assignOrganisationRole(Role::PRIVACY_OFFICER, $this->organisation);

    $snapshot = Snapshot::factory()
        ->recycle($this->organisation)
        ->withSnapshotData()
        ->create([
            'state' => InReview::class,
        ]);

    livewire(ViewSnapshot::class, [
        'record' => $snapshot->getRouteKey(),
    ])
        ->callAction('export_to_pdf')
        ->assertFileDownloaded();
});

it('can not export to pdf is no snapshot-data available', function (): void {
    $this->user->assignOrganisationRole(Role::PRIVACY_OFFICER, $this->organisation);

    $snapshot = Snapshot::factory()
        ->recycle($this->organisation)
        ->create([
            'state' => InReview::class,
        ]);

    livewire(ViewSnapshot::class, [
        'record' => $snapshot->getRouteKey(),
    ])
        ->assertActionDisabled('export_to_pdf');
});
