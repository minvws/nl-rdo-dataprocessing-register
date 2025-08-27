<?php

declare(strict_types=1);

use App\Enums\Media\MediaGroup;
use App\Models\Organisation;
use App\Services\PublicWebsite\Content\OrganisationGenerator;
use App\Services\PublicWebsite\PublicWebsiteFilesystem;
use App\Vendor\MediaLibrary\Media;
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

it('will copy the poster', function (): void {
    $this->mock(PublicWebsiteFilesystem::class)
        ->shouldReceive('write')
        ->once()
        ->shouldReceive('writeStream')
        ->once();

    $organisation = Organisation::factory()
        ->withPosterImage()
        ->createQuietly([
            'public_from' => null,
        ]);

    $organisationGenerator = $this->app->get(OrganisationGenerator::class);
    $organisationGenerator->generate($organisation);
});

it('will not fail if poster copy is skipped', function (): void {
    $this->mock(PublicWebsiteFilesystem::class)
        ->shouldReceive('write')
        ->once()
        ->shouldReceive('writeStream')
        ->never();

    $organisation = Organisation::factory()
        ->createQuietly([
            'public_from' => null,
        ]);
    Media::factory()
        ->for($organisation)
        ->createQuietly([
            'model_id' => $organisation->id,
            'model_type' => $organisation::class,
            'collection_name' => MediaGroup::ORGANISATION_POSTERS->value,
        ]);

    $organisationGenerator = $this->app->get(OrganisationGenerator::class);
    $organisationGenerator->generate($organisation);
});
