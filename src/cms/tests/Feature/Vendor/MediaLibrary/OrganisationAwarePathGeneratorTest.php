<?php

declare(strict_types=1);

namespace Tests\Feature\Vendor\MediaLibrary;

use App\Enums\Media\MediaGroup;
use App\Models\Organisation;
use App\Vendor\MediaLibrary\Media;
use App\Vendor\MediaLibrary\OrganisationAwarePathGenerator;

use function expect;
use function fake;
use function it;
use function sprintf;

it('generates organisation scoped paths', function (): void {
    $organisation = Organisation::factory()->create();
    $mediaType = fake()->randomElement(MediaGroup::cases());
    $media = Media::factory()
        ->for($organisation)
        ->createQuietly([
            'collection_name' => $mediaType->value,
        ]);

    $pathGenerator = $this->app->get(OrganisationAwarePathGenerator::class);
    $path = $pathGenerator->getPath($media);

    expect($path)
        ->toBe(sprintf('%s/%s/%s/', $organisation->id, $mediaType->value, $media->uuid));
});

it('generates organisation scoped paths when no tenant set', function (): void {
    $organisation = Organisation::factory()->create();
    $mediaType = fake()->randomElement(MediaGroup::cases());
    $media = Media::factory()
        ->for($organisation)
        ->createQuietly([
            'collection_name' => $mediaType->value,
        ]);

    $pathGenerator = $this->app->get(OrganisationAwarePathGenerator::class);
    $path = $pathGenerator->getPath($media);

    expect($path)
        ->toBe(sprintf('%s/%s/%s/', $organisation->id->toString(), $mediaType->value, $media->uuid));
});

it('is able to generate a path without organisation', function (): void {
    $mediaType = fake()->randomElement(MediaGroup::cases());
    $media = Media::factory()
        ->createQuietly([
            'organisation_id' => null,
            'collection_name' => $mediaType->value,
        ]);

    $pathGenerator = $this->app->get(OrganisationAwarePathGenerator::class);
    $path = $pathGenerator->getPath($media);

    expect($path)
        ->toBe(sprintf('%s/%s/', $mediaType->value, $media->uuid));
});
