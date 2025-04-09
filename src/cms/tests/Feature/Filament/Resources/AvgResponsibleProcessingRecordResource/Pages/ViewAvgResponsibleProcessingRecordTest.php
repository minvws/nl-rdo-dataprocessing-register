<?php

declare(strict_types=1);

use App\Filament\RelationManagers\SnapshotsRelationManager;
use App\Filament\Resources\AvgResponsibleProcessingRecordResource;
use App\Filament\Resources\AvgResponsibleProcessingRecordResource\Pages\ViewAvgResponsibleProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\PublicWebsiteCheck;
use App\Models\PublicWebsiteSnapshotEntry;
use App\Models\Snapshot;
use App\Models\States\Snapshot\Established;
use App\Services\DateFormatService;
use Carbon\CarbonImmutable;
use Tests\Helpers\ConfigHelper;

use function Pest\Livewire\livewire;

it('can load the ViewAvgResponsibleProcessingRecord page', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(AvgResponsibleProcessingRecordResource::getUrl('view', ['record' => $avgResponsibleProcessingRecord->id]))
        ->assertSuccessful();
});

it('can create a snapshot', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create();

    expect($avgResponsibleProcessingRecord->snapshots->count())
        ->toBe(0);

    livewire(ViewAvgResponsibleProcessingRecord::class, ['record' => $avgResponsibleProcessingRecord->id])
        ->callAction('snapshot_create')
        ->assertNotified(__('snapshot.created'))
        ->assertDispatched(SnapshotsRelationManager::REFRESH_TABLE_EVENT);

    expect($avgResponsibleProcessingRecord->refresh()->snapshots->count())
        ->toBe(1);
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
    livewire(ViewAvgResponsibleProcessingRecord::class, [
        'record' => $avgResponsibleProcessingRecord->getRouteKey(),
    ])
        ->assertSee(__('general.published_at', ['published_at' => $expectedPublishedAt]));
});

it('shows current state as not published', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create();

    livewire(ViewAvgResponsibleProcessingRecord::class, [
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

    livewire(ViewAvgResponsibleProcessingRecord::class, [
        'record' => $avgResponsibleProcessingRecord->getRouteKey(),
    ])
        ->assertSee(__('public_website.public_from_section.public_state_public'))
        ->assertSee(__('public_website.public_from_section.public_history_since', ['start' => '01-03-2020 00:00']))
        ->assertSee(__('public_website.public_from_section.public_history_from_to', [
            'start' => '01-01-2020 00:00',
            'end' => '01-02-2020 00:00',
        ]));
});
