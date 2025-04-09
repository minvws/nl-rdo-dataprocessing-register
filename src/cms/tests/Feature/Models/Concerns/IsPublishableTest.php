<?php

declare(strict_types=1);

use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\EntityNumber;
use App\Models\PublicWebsiteCheck;
use App\Models\PublicWebsiteSnapshotEntry;
use App\Models\Snapshot;
use App\Models\States\Snapshot\Established;

it('can get the public identifier', function (): void {
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

it('can get the public from', function (): void {
    $publicFrom = fake()->anyDate();

    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create([
            'public_from' => $publicFrom,
        ]);

    expect($avgResponsibleProcessingRecord->getPublicFrom()->equalTo($publicFrom))
        ->toBeTrue();
});

it('can get the public from when null', function (): void {
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

it('is not published when no public-website-check exists', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create();

    expect($avgResponsibleProcessingRecord->isPublished())
        ->toBeFalse();
});

it('is not published when public build exists but not for this snapshot', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create([
            'public_from' => fake()->dateTimeBetween(),
        ]);
    PublicWebsiteCheck::factory()->create();

    expect($avgResponsibleProcessingRecord->isPublished())
        ->toBeFalse();
});

it('is published when public-website-check exists and a established snapshot exists', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create();
    $snapshot = Snapshot::factory()
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->create(['state' => Established::$name]);
    $publicWebsiteCheck = PublicWebsiteCheck::factory()
        ->createForSnapshot($snapshot->id);
    PublicWebsiteSnapshotEntry::factory()
        ->create([
            'last_public_website_check_id' => $publicWebsiteCheck,
            'snapshot_id' => $snapshot->id,
            'end_date' => null,
        ]);

    expect($avgResponsibleProcessingRecord->isPublished())
        ->toBeTrue();
});
