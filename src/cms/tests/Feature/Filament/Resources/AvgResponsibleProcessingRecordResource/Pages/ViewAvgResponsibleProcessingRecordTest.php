<?php

declare(strict_types=1);

use App\Enums\RegisterLayout;
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
use Tests\Helpers\ConfigTestHelper;
use Tests\Helpers\Model\OrganisationTestHelper;
use Tests\Helpers\Model\UserTestHelper;

it('can load the page with all layouts', function (RegisterLayout $registerLayout): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation, ['register_layout' => $registerLayout]);

    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentUser($user)
        ->get(AvgResponsibleProcessingRecordResource::getUrl('view', [
            'record' => $avgResponsibleProcessingRecord,
        ]))
        ->assertSuccessful();
})->with(RegisterLayout::cases());

it('can create a snapshot', function (): void {
    $organisation = OrganisationTestHelper::create();
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create();

    expect($avgResponsibleProcessingRecord->snapshots->count())
        ->toBe(0);

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ViewAvgResponsibleProcessingRecord::class, ['record' => $avgResponsibleProcessingRecord->id])
        ->callAction('snapshot_create')
        ->assertNotified(__('snapshot.created'))
        ->assertDispatched(SnapshotsRelationManager::REFRESH_TABLE_EVENT);

    expect($avgResponsibleProcessingRecord->refresh()->snapshots->count())
        ->toBe(1);
});

it('shows the link to the public page when the record is published', function (): void {
    $publishedAt = fake()->dateTimeBetween('-2 weeks', '-1 week', 'utc');

    $organisation = OrganisationTestHelper::create();
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
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
        ->setTimezone(ConfigTestHelper::get('app.display_timezone'))
        ->format(DateFormatService::FORMAT_DATE);

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ViewAvgResponsibleProcessingRecord::class, [
            'record' => $avgResponsibleProcessingRecord->getRouteKey(),
        ])
        ->assertSee(__('general.published_at', ['published_at' => $expectedPublishedAt]));
});

it('shows current state as not published', function (): void {
    $organisation = OrganisationTestHelper::create();
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ViewAvgResponsibleProcessingRecord::class, [
            'record' => $avgResponsibleProcessingRecord->getRouteKey(),
        ])
        ->assertSee(__('public_website.public_from_section.public_state_not_public'));
});

it('shows the data of the publications when the record is published', function (): void {
    $organisation = OrganisationTestHelper::create();
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
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

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ViewAvgResponsibleProcessingRecord::class, [
            'record' => $avgResponsibleProcessingRecord->getRouteKey(),
        ])
        ->assertSee(__('public_website.public_from_section.public_state_public'))
        ->assertSee(__('public_website.public_from_section.public_history_since', ['start' => '01-03-2020 01:00']))
        ->assertSee(__('public_website.public_from_section.public_history_from_to', [
            'start' => '1 januari 2020 01:00',
            'end' => '1 februari 2020 01:00',
        ]));
});
