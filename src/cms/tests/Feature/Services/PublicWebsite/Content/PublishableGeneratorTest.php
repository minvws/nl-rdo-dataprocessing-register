<?php

declare(strict_types=1);

use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Organisation;
use App\Models\Snapshot;
use App\Models\States\Snapshot\Established;
use App\Services\PublicWebsite\Content\PublishableGenerator;
use App\Services\PublicWebsite\PublicWebsiteFilesystem;
use Carbon\CarbonImmutable;

it('will write to the filesystem', function (): void {
    $publicWebsiteFilesystem = $this->createMock(PublicWebsiteFilesystem::class);
    $publicWebsiteFilesystem->expects($this->once())
        ->method('write');

    $organisation = Organisation::factory()
        ->create([
            'public_from' => null,
        ]);
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create([
            'public_from' => CarbonImmutable::yesterday(),
        ]);
    Snapshot::factory()
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->recycle($organisation)
        ->withSnapshotData([
            'public_markdown' => fake()->sentence(),
        ])
        ->create([
            'state' => Established::class,
        ]);

    /** @var PublishableGenerator $publishableGenerator */
    $publishableGenerator = $this->app->make(PublishableGenerator::class, ['publicWebsiteFilesystem' => $publicWebsiteFilesystem]);
    $publishableGenerator->generate($avgResponsibleProcessingRecord);
});

it('will not write to the filesystem if no snapshot available', function (): void {
    $publicWebsiteFilesystem = $this->createMock(PublicWebsiteFilesystem::class);
    $publicWebsiteFilesystem->expects($this->never())
        ->method('write');

    $organisation = Organisation::factory()
        ->create([
            'public_from' => null,
        ]);
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create([
            'public_from' => CarbonImmutable::yesterday(),
        ]);

    /** @var PublishableGenerator $publishableGenerator */
    $publishableGenerator = $this->app->make(PublishableGenerator::class, ['publicWebsiteFilesystem' => $publicWebsiteFilesystem]);
    $publishableGenerator->generate($avgResponsibleProcessingRecord);
});

it('will not write to the filesystem if no snapshot-data available', function (): void {
    $publicWebsiteFilesystem = $this->createMock(PublicWebsiteFilesystem::class);
    $publicWebsiteFilesystem->expects($this->never())
        ->method('write');

    $organisation = Organisation::factory()
        ->create([
            'public_from' => null,
        ]);
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create([
            'public_from' => CarbonImmutable::yesterday(),
        ]);
    Snapshot::factory()
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->recycle($organisation)
        ->create([
            'state' => Established::class,
        ]);

    /** @var PublishableGenerator $publishableGenerator */
    $publishableGenerator = $this->app->make(PublishableGenerator::class, ['publicWebsiteFilesystem' => $publicWebsiteFilesystem]);
    $publishableGenerator->generate($avgResponsibleProcessingRecord);
});
