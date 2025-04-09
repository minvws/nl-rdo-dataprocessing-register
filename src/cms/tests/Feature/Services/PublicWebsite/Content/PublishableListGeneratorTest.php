<?php

declare(strict_types=1);

use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Organisation;
use App\Models\Snapshot;
use App\Models\SnapshotData;
use App\Models\States\Snapshot\Established;
use App\Services\PublicWebsite\Content\PublishableListGenerator;
use App\Services\PublicWebsite\PublicWebsiteFilesystem;
use Carbon\CarbonImmutable;

it('will write to the filesystem if organisation has publishable records', function (): void {
    $publicWebsiteFilesystem = $this->createMock(PublicWebsiteFilesystem::class);
    $publicWebsiteFilesystem->expects($this->once())
        ->method('write');

    $organisation = Organisation::factory()
        ->create([
            'public_from' => CarbonImmutable::yesterday(),
        ]);
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create([
            'public_from' => CarbonImmutable::yesterday(),
        ]);
    $snapshot = Snapshot::factory()
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->recycle($organisation)
        ->create([
            'state' => Established::class,
        ]);
    SnapshotData::factory()
        ->for($snapshot)
        ->create([
            'public_markdown' => 'Nam quae temporibus et molestiae voluptatibus enim.',
        ]);


    /** @var PublishableListGenerator $publishableListGenerator */
    $publishableListGenerator = $this->app->make(PublishableListGenerator::class, ['publicWebsiteFilesystem' => $publicWebsiteFilesystem]);
    $publishableListGenerator->generate($organisation);
});


it('will not write to the filesystem if organisation has no publishable records', function (): void {
    $publicWebsiteFilesystem = $this->createMock(PublicWebsiteFilesystem::class);
    $publicWebsiteFilesystem->expects($this->never())
        ->method('write');

    $organisation = Organisation::factory()
        ->create();

    /** @var PublishableListGenerator $publishableListGenerator */
    $publishableListGenerator = $this->app->make(PublishableListGenerator::class, ['publicWebsiteFilesystem' => $publicWebsiteFilesystem]);
    $publishableListGenerator->generate($organisation);
});
