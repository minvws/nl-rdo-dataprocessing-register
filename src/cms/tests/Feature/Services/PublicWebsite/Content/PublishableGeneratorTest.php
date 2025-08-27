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
    $this->mock(PublicWebsiteFilesystem::class)
        ->shouldReceive('write')
        ->once();

    $organisation = Organisation::factory()
        ->createQuietly([
            'public_from' => null,
        ]);
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->createQuietly([
            'public_from' => CarbonImmutable::yesterday(),
        ]);
    Snapshot::factory()
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->recycle($organisation)
        ->withSnapshotData([
            'public_markdown' => fake()->sentence(),
        ])
        ->createQuietly([
            'state' => Established::class,
        ]);

    $publishableGenerator = $this->app->get(PublishableGenerator::class);
    $publishableGenerator->generate($avgResponsibleProcessingRecord);
});

it('will not write to the filesystem if no snapshot available', function (): void {
    $this->mock(PublicWebsiteFilesystem::class)
        ->shouldReceive('write')
        ->never();

    $organisation = Organisation::factory()
        ->createQuietly([
            'public_from' => null,
        ]);
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->createQuietly([
            'public_from' => CarbonImmutable::yesterday(),
        ]);

    $publishableGenerator = $this->app->get(PublishableGenerator::class);
    $publishableGenerator->generate($avgResponsibleProcessingRecord);
});

it('will not write to the filesystem if no snapshot-data available', function (): void {
    $this->mock(PublicWebsiteFilesystem::class)
        ->shouldReceive('write')
        ->never();

    $organisation = Organisation::factory()
        ->createQuietly([
            'public_from' => null,
        ]);
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->createQuietly([
            'public_from' => CarbonImmutable::yesterday(),
        ]);
    Snapshot::factory()
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->recycle($organisation)
        ->createQuietly([
            'state' => Established::class,
        ]);

    $publishableGenerator = $this->app->get(PublishableGenerator::class);
    $publishableGenerator->generate($avgResponsibleProcessingRecord);
});
