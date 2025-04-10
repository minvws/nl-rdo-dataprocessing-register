<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Filament\Forms\Components\Select\ParentSelect;
use App\Filament\RelationManagers\SnapshotsRelationManager;
use App\Filament\Resources\AvgResponsibleProcessingRecordResource;
use App\Filament\Resources\AvgResponsibleProcessingRecordResource\Pages\EditAvgResponsibleProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\EntityNumber;
use App\Models\PublicWebsiteCheck;
use App\Models\PublicWebsiteSnapshotEntry;
use App\Models\Snapshot;
use App\Models\States\Snapshot\Established;
use App\Models\Tag;
use App\Models\User;
use App\Services\DateFormatService;
use App\Services\EntityNumberService;
use Carbon\CarbonImmutable;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Mockery\MockInterface;
use Tests\Helpers\ConfigHelper;

use function Pest\Livewire\livewire;

it('loads the edit page', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(
        AvgResponsibleProcessingRecordResource::getUrl('edit', ['record' => $avgResponsibleProcessingRecord->id]),
    )->assertSuccessful();
});

it('can create a snapshot', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create();

    expect($avgResponsibleProcessingRecord->snapshots->count())
        ->toBe(0);

    livewire(EditAvgResponsibleProcessingRecord::class, ['record' => $avgResponsibleProcessingRecord->id])
        ->callAction('snapshot_create')
        ->assertNotified(__('snapshot.created'))
        ->assertDispatched(SnapshotsRelationManager::REFRESH_TABLE_EVENT);

    expect($avgResponsibleProcessingRecord->refresh()->snapshots->count())
        ->toBe(1);
});

it('can be saved', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($this->organisation)
        ->withValidState()
        ->create([
            'has_security' => false,
            'outside_eu' => false,
            'outside_eu_protection_level' => true,
        ]);
    $name = fake()->uuid();

    livewire(EditAvgResponsibleProcessingRecord::class, [
        'record' => $avgResponsibleProcessingRecord->getRouteKey(),
    ])
        ->fillForm([
            'name' => $name,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $avgResponsibleProcessingRecord->refresh();
    expect($avgResponsibleProcessingRecord->name)
        ->toBe($name);
});

it('can be edit & saved with update-permissions for the record, but not for sub-entities', function (): void {
    $user = User::factory()
        ->hasAttached($this->organisation)
        ->withValidOtpRegistration()
        ->create();

    $user->assignOrganisationRole(Role::INPUT_PROCESSOR, $this->organisation);
    $this->be($user);
    Filament::setTenant($this->organisation);

    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($this->organisation)
        ->withProcessors()
        ->withResponsibles()
        ->withSystems()
        ->create([
            'has_security' => false,
            'outside_eu' => false,
            'outside_eu_protection_level' => true,
        ]);
    $name = fake()->uuid();

    livewire(EditAvgResponsibleProcessingRecord::class, [
        'record' => $avgResponsibleProcessingRecord->getRouteKey(),
    ])
        ->fillForm([
            'name' => $name,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $avgResponsibleProcessingRecord->refresh();
    expect($avgResponsibleProcessingRecord->name)
        ->toBe($name);
});

it('can see the dpia field', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create([
            'geb_dpia_executed' => false,
        ]);

    livewire(EditAvgResponsibleProcessingRecord::class, [
        'record' => $avgResponsibleProcessingRecord->getRouteKey(),
    ])
        ->assertSee(__('avg_responsible_processing_record.geb_dpia_automated'));
});

it('can not see the dpia field', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create([
            'geb_dpia_executed' => true,
        ]);

    livewire(EditAvgResponsibleProcessingRecord::class, [
        'record' => $avgResponsibleProcessingRecord->getRouteKey(),
    ])
        ->assertDontSee(__('avg_responsible_processing_record.geb_dpia_automated'));
});

it('can save a dpia subfield', function (): void {
    /** @var AvgResponsibleProcessingRecord $avgResponsibleProcessingRecord */
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($this->organisation)
        ->withValidState()
        ->create([
            'geb_dpia_executed' => false,
            'geb_dpia_automated' => false,
            'geb_dpia_large_scale_processing' => false,
            'geb_dpia_large_scale_monitoring' => false,
            'geb_dpia_list_required' => false,
            'geb_dpia_criteria_wp248' => false,
            'geb_dpia_high_risk_freedoms' => true,
        ]);

    livewire(EditAvgResponsibleProcessingRecord::class, [
        'record' => $avgResponsibleProcessingRecord->getRouteKey(),
    ])
        ->fillForm([
            'geb_dpia_criteria_wp248' => true,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $avgResponsibleProcessingRecord->refresh();
    expect($avgResponsibleProcessingRecord->geb_dpia_executed)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_criteria_wp248)->toBeTrue()
        ->and($avgResponsibleProcessingRecord->geb_dpia_high_risk_freedoms)->toBeFalse();
});

it('can see the protection level description field', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create([
            'outside_eu' => true,
            'outside_eu_protection_level' => false,
        ]);

    livewire(EditAvgResponsibleProcessingRecord::class, [
        'record' => $avgResponsibleProcessingRecord->getRouteKey(),
    ])
        ->assertSee(__('avg_responsible_processing_record.outside_eu_protection_level_description'));
});

it('shows the link to the public page when the record is published', function (): void {
    $publishedAt = fake()->dateTimeBetween('-2 weeks', '-1 week', 'utc');

    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create([
            'public_from' => $publishedAt,
        ]);
    Snapshot::factory()
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->create([
            'state' => Established::class,
        ]);
    $avgResponsibleProcessingRecord->refresh();

    $expectedPublishedAt = CarbonImmutable::instance($publishedAt)
        ->setTimezone(ConfigHelper::get('app.display_timezone'))
        ->format(DateFormatService::FORMAT_DATE);
    livewire(EditAvgResponsibleProcessingRecord::class, [
        'record' => $avgResponsibleProcessingRecord->getRouteKey(),
    ])
        ->assertSee(__('general.published_at', ['published_at' => $expectedPublishedAt]));
});

it('shows current state as not published', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create();

    livewire(EditAvgResponsibleProcessingRecord::class, [
        'record' => $avgResponsibleProcessingRecord->getRouteKey(),
    ])
        ->assertSee(__('public_website.public_from_section.public_state_not_public'));
});

it('shows the data of the publications when the record is published', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create();
    $snapshot = Snapshot::factory()
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->create([
            'state' => Established::class,
        ]);
    $publicWebsiteCheck = PublicWebsiteCheck::factory()
        ->createForSnapshot($snapshot->id);
    PublicWebsiteSnapshotEntry::factory()
        ->create([
            'last_public_website_check_id' => $publicWebsiteCheck,
            'snapshot_id' => $snapshot->id,
            'start_date' => '2020-01-01 00:00:00',
            'end_date' => '2020-02-01 00:00:00',
        ]);
    PublicWebsiteSnapshotEntry::factory()
        ->create([
            'last_public_website_check_id' => $publicWebsiteCheck,
            'snapshot_id' => $snapshot->id,
            'start_date' => '2020-03-01 00:00:00',
            'end_date' => null,
        ]);

    livewire(EditAvgResponsibleProcessingRecord::class, [
        'record' => $avgResponsibleProcessingRecord->getRouteKey(),
    ])
        ->assertSee(__('public_website.public_from_section.public_state_public'))
        ->assertSee(__('public_website.public_from_section.public_history_since', ['start' => '01-03-2020 00:00']))
        ->assertSee(__('public_website.public_from_section.public_history_from_to', [
            'start' => '01-01-2020 00:00',
            'end' => '01-02-2020 00:00',
        ]));
});

it('shows a parent record from the same organisation', function (): void {
    $parentAvgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create();

    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create();

    livewire(EditAvgResponsibleProcessingRecord::class, [
        'record' => $avgResponsibleProcessingRecord->getRouteKey(),
    ])
        ->assertFormFieldExists(
            'parent_id',
            static function (ParentSelect $field) use ($parentAvgResponsibleProcessingRecord): bool {
                return $field->getOptions() === [
                    $parentAvgResponsibleProcessingRecord->id => $parentAvgResponsibleProcessingRecord->name,
                ];
            },
        );
});

it('does not show a parent record from another organisation', function (): void {
    // record from another organisation, should not show up in the form
    AvgResponsibleProcessingRecord::factory()
        ->create();

    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create();

    livewire(EditAvgResponsibleProcessingRecord::class, [
        'record' => $avgResponsibleProcessingRecord->getRouteKey(),
    ])
        ->assertFormFieldExists(
            'parent_id',
            static function (ParentSelect $field): bool {
                return $field->getOptions() === [];
            },
        );
});

it('does not create a snapshot on unsaved changes', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create();

    livewire(EditAvgResponsibleProcessingRecord::class, [
        'record' => $avgResponsibleProcessingRecord->getRouteKey(),
    ])
        ->fillForm([
            'name' => 'unsaved change',
        ])
        ->callAction('snapshot_create')
        ->assertNotified(__('snapshot.unsaved_changes'))
        ->assertNotNotified(__('snapshot.created'));
});

it('can be attached to a tag', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($this->organisation)
        ->withValidState()
        ->create([
            'has_security' => false,
            'outside_eu' => false,
            'outside_eu_protection_level' => true,
        ]);
    $tag = Tag::factory()
        ->recycle($this->organisation)
        ->create();
    expect($avgResponsibleProcessingRecord->tags->count())
        ->toBe(0);

    livewire(EditAvgResponsibleProcessingRecord::class, [
        'record' => $avgResponsibleProcessingRecord->getRouteKey(),
    ])
        ->fillForm([
            'tags' => [$tag->id->toString()],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $avgResponsibleProcessingRecord->refresh();
    expect($avgResponsibleProcessingRecord->tags->count())
        ->toBe(1);
});

it('can do a lookup for a tag', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create();
    $tag = Tag::factory()
        ->recycle($this->organisation)
        ->create();

    livewire(EditAvgResponsibleProcessingRecord::class, [
        'record' => $avgResponsibleProcessingRecord->getRouteKey(),
    ])
        ->assertFormFieldExists(
            'tags',
            static function (Select $field) use ($tag): bool {
                return $field->getSearchResults($tag->name) === [
                    $tag->id->toString() => $tag->name,
                ];
            },
        );
});

it('shows a (required) validation error for remarks when avg_goal_legal_base selected', function (): void {
    AvgResponsibleProcessingRecord::factory()
        ->create();

    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($this->organisation)
        ->withValidState()
        ->create();

    livewire(EditAvgResponsibleProcessingRecord::class, [
        'record' => $avgResponsibleProcessingRecord->getRouteKey(),
    ])
        ->fillForm([
            'avgGoals' => [
                0 => [
                    'goal' => null,
                    'avg_goal_legal_base' => fake()->randomElement(__('avg_goal_legal_base.options')),
                    'remarks' => null,
                ],
            ],
        ])
        ->call('save')
        ->assertHasFormErrors(['avgGoals.0.remarks' => 'required']);
});

it('does not allow using a parent from another organisation', function (): void {
    // record from another organisation, should not be possible to use it
    $otherOrganisationAvgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create();

    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($this->organisation)
        ->withValidState()
        ->create();

    livewire(EditAvgResponsibleProcessingRecord::class, [
        'record' => $avgResponsibleProcessingRecord->id,
    ])
        ->fillForm([
            'parent_id' => $otherOrganisationAvgResponsibleProcessingRecord->id,
        ])
        ->call('save')
        ->assertHasFormErrors(['parent_id' => 'in']);
});

it('can be cloned', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($this->organisation)
        ->withAllRelatedEntities()
        ->create();

    $entityNumber = EntityNumber::factory()
        ->create();

    $this->mock(EntityNumberService::class, static function (MockInterface $mock) use ($entityNumber): void {
        $mock->expects('generate')
            ->andReturn($entityNumber);
    });

    livewire(EditAvgResponsibleProcessingRecord::class, [
        'record' => $avgResponsibleProcessingRecord->getRouteKey(),
    ])
        ->callAction('clone')
        ->assertRedirect();

    $avgResponsibleProcessingRecordClone = AvgResponsibleProcessingRecord::query()
        ->where('entity_number_id', $entityNumber->id)
        ->firstOrFail();

    expect($avgResponsibleProcessingRecordClone->entity_number_id)->not()->toBe($avgResponsibleProcessingRecord->entity_number_id)
        ->and($avgResponsibleProcessingRecordClone->name)->toBe($avgResponsibleProcessingRecord->name)
        ->and($avgResponsibleProcessingRecordClone->public_from?->toJson())->toBe($avgResponsibleProcessingRecord->public_from?->toJson());

    expect($avgResponsibleProcessingRecordClone->fgRemark)->toBeNull();
    expect($avgResponsibleProcessingRecordClone->snapshots)->toBeEmpty();

    expect($avgResponsibleProcessingRecordClone->avgGoals->pluck('id')->toArray())
        ->toBe($avgResponsibleProcessingRecord->avgGoals->pluck('id')->toArray());

    expect($avgResponsibleProcessingRecordClone->contactPersons->pluck('id')->toArray())
        ->toBe($avgResponsibleProcessingRecord->contactPersons->pluck('id')->toArray());

    expect($avgResponsibleProcessingRecordClone->dataBreachRecords->pluck('id')->toArray())
        ->toBe($avgResponsibleProcessingRecord->dataBreachRecords->pluck('id')->toArray());

    expect($avgResponsibleProcessingRecordClone->documents->pluck('id')->toArray())
        ->toBe($avgResponsibleProcessingRecord->documents->pluck('id')->toArray());

    expect($avgResponsibleProcessingRecordClone->processors->pluck('id')->toArray())
        ->toBe($avgResponsibleProcessingRecord->processors->pluck('id')->toArray());

    expect($avgResponsibleProcessingRecordClone->receivers->pluck('id')->toArray())
        ->toBe($avgResponsibleProcessingRecord->receivers->pluck('id')->toArray());

    expect($avgResponsibleProcessingRecordClone->remarks->pluck('body')->toArray())
        ->toBe($avgResponsibleProcessingRecord->remarks->pluck('body')->toArray());

    expect($avgResponsibleProcessingRecordClone->responsibles->pluck('id')->toArray())
        ->toBe($avgResponsibleProcessingRecord->responsibles->pluck('id')->toArray());

    expect($avgResponsibleProcessingRecordClone->stakeholders->pluck('id')->toArray())
        ->toBe($avgResponsibleProcessingRecord->stakeholders->pluck('id')->toArray());

    expect($avgResponsibleProcessingRecordClone->systems->pluck('id')->toArray())
        ->toBe($avgResponsibleProcessingRecord->systems->pluck('id')->toArray());

    expect($avgResponsibleProcessingRecordClone->tags->pluck('id'))
        ->toEqual($avgResponsibleProcessingRecord->tags->pluck('id'));
});
