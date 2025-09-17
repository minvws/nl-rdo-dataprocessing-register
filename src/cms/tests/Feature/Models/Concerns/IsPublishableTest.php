<?php

declare(strict_types=1);

use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\EntityNumber;
use App\Models\Snapshot;
use App\Models\States\Snapshot\Established;
use App\Models\StaticWebsiteCheck;
use App\Models\StaticWebsiteSnapshotEntry;

it('can get the static identifier', function (): void {
    $number = fake()->word();

    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create([
            'entity_number_id' => EntityNumber::factory(state: [
                'number' => $number,
            ]),
        ]);

    expect($avgResponsibleProcessingRecord->getPublicIdentifier())
        ->toBe($number);
});

it('can get the static from', function (): void {
    $staticFrom = fake()->anyDate();

    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create([
            'public_from' => $staticFrom,
        ]);

    expect($avgResponsibleProcessingRecord->getPublicFrom()->equalTo($staticFrom))
        ->toBeTrue();
});

it('can get the static from when null', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create([
            'public_from' => null,
        ]);

    expect($avgResponsibleProcessingRecord->getPublicFrom())
        ->toBeNull();
});

it('is not published when public_from is null', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create([
            'public_from' => null,
        ]);

    expect($avgResponsibleProcessingRecord->isPublished())
        ->toBeFalse();
});

it('is not published when no static-website-check exists', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create();

    expect($avgResponsibleProcessingRecord->isPublished())
        ->toBeFalse();
});

it('is not published when static build exists but not for this snapshot', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create([
            'public_from' => fake()->dateTimeBetween(),
        ]);
    StaticWebsiteCheck::factory()->create();

    expect($avgResponsibleProcessingRecord->isPublished())
        ->toBeFalse();
});

it('is published when static-website-check exists and a established snapshot exists', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create();
    $snapshot = Snapshot::factory()
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->create(['state' => Established::$name]);
    $staticWebsiteCheck = StaticWebsiteCheck::factory()
        ->createForSnapshot($snapshot->id);
    StaticWebsiteSnapshotEntry::factory()
        ->create([
            'last_static_website_check_id' => $staticWebsiteCheck,
            'snapshot_id' => $snapshot->id,
            'end_date' => null,
        ]);

    expect($avgResponsibleProcessingRecord->isPublished())
        ->toBeTrue();
});
