<?php

declare(strict_types=1);

use App\Events\Models\PublishableEvent;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Organisation;
use App\Models\Snapshot;
use App\Models\States\Snapshot\Established;
use App\Services\PublicWebsite\Content\OrganisationGenerator;
use App\Services\PublicWebsite\PublicWebsiteFilesystem;
use Carbon\CarbonImmutable;

it('will update the published_at when generated', function (): void {
    $publicFrom = CarbonImmutable::yesterday();
    $organisation = Organisation::factory()
        ->create([
            'published_at' => $publicFrom,
        ]);

    /** @var OrganisationGenerator $organisationGenerator */
    $organisationGenerator = $this->app->get(OrganisationGenerator::class);
    $organisationGenerator->generate($organisation);

    $organisation->refresh();
    expect($organisation->published_at->toJSON())
        ->toBe($publicFrom->toJSON());
});

it('will trigger the publish event for related records', function (): void {
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

    Event::fake(PublishableEvent::class);

    /** @var OrganisationGenerator $organisationGenerator */
    $organisationGenerator = $this->app->get(OrganisationGenerator::class);
    $organisationGenerator->generate($organisation);

    Event::assertDispatched(PublishableEvent::class, static function (PublishableEvent $event) use ($avgResponsibleProcessingRecord) {
        return $event->publishable->id === $avgResponsibleProcessingRecord->id;
    });
});

it('will copy the poster', function (): void {
    $publicWebsiteFilesystem = $this->createMock(PublicWebsiteFilesystem::class);
    $publicWebsiteFilesystem->expects($this->once())
        ->method('writeStream');

    $organisation = Organisation::factory()
        ->withPosterImage()
        ->create([
            'public_from' => null,
        ]);

    /** @var OrganisationGenerator $organisationGenerator */
    $organisationGenerator = $this->app->make(OrganisationGenerator::class, ['publicWebsiteFilesystem' => $publicWebsiteFilesystem]);
    $organisationGenerator->generate($organisation);
});
