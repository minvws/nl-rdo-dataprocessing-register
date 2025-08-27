<?php

declare(strict_types=1);

use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Organisation;
use App\Models\PublicWebsiteTree;
use App\Models\Snapshot;
use App\Models\SnapshotData;
use App\Models\States\Snapshot\Established;
use App\Services\StaticWebsite\Content\PublishableListGenerator;
use App\Services\StaticWebsite\StaticWebsiteFilesystem;
use Carbon\CarbonImmutable;

it('will write to the filesystem if organisation has publishable records', function (): void {
    $this->mock(StaticWebsiteFilesystem::class)
        ->shouldReceive('write')
        ->once();

    $organisation = Organisation::factory()
        ->createQuietly([
            'public_from' => CarbonImmutable::yesterday(),
        ]);
    $publicWebsiteTree = PublicWebsiteTree::factory()
        ->recycle($organisation)
        ->createQuietly([
            'public_from' => CarbonImmutable::yesterday(),
        ]);
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->createQuietly([
            'public_from' => CarbonImmutable::yesterday(),
        ]);
    $snapshot = Snapshot::factory()
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->recycle($organisation)
        ->createQuietly([
            'state' => Established::class,
        ]);
    SnapshotData::factory()
        ->for($snapshot)
        ->createQuietly([
            'public_markdown' => 'Nam quae temporibus et molestiae voluptatibus enim.',
        ]);

    $publishableListGenerator = $this->app->get(PublishableListGenerator::class);
    $publishableListGenerator->generate($publicWebsiteTree, []);
});

it('will not write to the filesystem if organisation has no publishable records', function (): void {
    $this->mock(StaticWebsiteFilesystem::class)
        ->shouldReceive('write')
        ->never();

    $organisation = Organisation::factory()
        ->createQuietly();
    $publicWebsiteTree = PublicWebsiteTree::factory()
        ->recycle($organisation)
        ->createQuietly(['public_from' => CarbonImmutable::yesterday()]);

    $publishableListGenerator = $this->app->get(PublishableListGenerator::class);
    $publishableListGenerator->generate($publicWebsiteTree, []);
});

it('will not write to the filesystem if no organisation linked', function (): void {
    $this->mock(StaticWebsiteFilesystem::class)
        ->shouldReceive('write')
        ->never();

    $publicWebsiteTree = PublicWebsiteTree::factory()->createQuietly([
        'organisation_id' => null,
        'public_from' => CarbonImmutable::yesterday(),
    ]);

    $publishableListGenerator = $this->app->get(PublishableListGenerator::class);
    $publishableListGenerator->generate($publicWebsiteTree, []);
});
