<?php

declare(strict_types=1);

use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Organisation;
use App\Models\Snapshot;
use App\Models\States\Snapshot\Established;
use App\Services\StaticWebsite\Content\PublishableGenerator;
use App\Services\StaticWebsite\StaticWebsiteFilesystem;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;

it('will write to the filesystem', function (): void {
    $organisation = Organisation::factory()
        ->create([
            'public_from' => null,
        ]);
    /** @var AvgResponsibleProcessingRecord $avgResponsibleProcessingRecord */
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

    $this->mock(StaticWebsiteFilesystem::class)
        ->shouldReceive('write')
        ->once()
        ->withSomeOfArgs(sprintf('organisatie//verwerkingen/%s.html', Str::slug($avgResponsibleProcessingRecord->getPublicIdentifier())));

    $publishableGenerator = $this->app->get(PublishableGenerator::class);
    $publishableGenerator->generate($avgResponsibleProcessingRecord, []);
});

it('will write to the filesystem in a subfolder', function (): void {
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

    $parentSlug = fake()->slug();
    $this->mock(StaticWebsiteFilesystem::class)
        ->shouldReceive('write')
        ->once()
        ->withSomeOfArgs(
            sprintf('organisatie/%s//verwerkingen/%s.html', $parentSlug, Str::slug($avgResponsibleProcessingRecord->getPublicIdentifier())),
        );

    $publishableGenerator = $this->app->get(PublishableGenerator::class);
    $publishableGenerator->generate($avgResponsibleProcessingRecord, [$parentSlug]);
});

it('will not write to the filesystem if no snapshot available', function (): void {
    $this->mock(StaticWebsiteFilesystem::class)
        ->shouldReceive('write')
        ->never();

    $organisation = Organisation::factory()
        ->create([
            'public_from' => null,
        ]);
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create([
            'public_from' => CarbonImmutable::yesterday(),
        ]);

    $publishableGenerator = $this->app->get(PublishableGenerator::class);
    $publishableGenerator->generate($avgResponsibleProcessingRecord, []);
});

it('will not write to the filesystem if no snapshot-data available', function (): void {
    $this->mock(StaticWebsiteFilesystem::class)
        ->shouldReceive('write')
        ->never();

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

    $publishableGenerator = $this->app->get(PublishableGenerator::class);
    $publishableGenerator->generate($avgResponsibleProcessingRecord, []);
});
