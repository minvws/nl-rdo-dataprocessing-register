<?php

declare(strict_types=1);

namespace Tests\Feature\Vendor\MediaLibrary;

use App\Enums\Media\MediaGroup;
use App\Models\Organisation;
use App\Models\User;
use App\Vendor\MediaLibrary\Media;
use App\Vendor\MediaLibrary\OrganisationAwarePathGenerator;
use Mockery;

use function expect;
use function fake;
use function it;
use function Pest\Laravel\actingAs;
use function sprintf;

it('generates organisation scoped paths', function (): void {
    $organisation = Organisation::factory()->create();
    $mediaType = fake()->randomElement(MediaGroup::cases());

    $media = Mockery::mock(Media::class)
        ->allows('getAttribute')
        ->with('uuid')
        ->andReturns(fake()->uuid())
        ->getMock()
        ->allows('getAttribute')
        ->with('collection_name')
        ->andReturns($mediaType->value)
        ->getMock()
        ->allows('getAttribute')
        ->with('organisation_id')
        ->andReturns($organisation->id)
        ->getMock()
        ->allows('getKey')
        ->andReturns(fake()->randomNumber())
        ->getMock();

    $pathGenerator = $this->app->get(OrganisationAwarePathGenerator::class);
    $path = $pathGenerator->getPath($media);

    expect($path)
        ->toBe(sprintf('%s/%s/%s/', $organisation->id, $mediaType->value, $media->uuid));
});

it('generates organisation scoped paths when no tenant set', function (): void {
    $organisation = Organisation::factory()->create();
    $user = User::factory()->hasAttached($organisation)->create();

    actingAs($user);
    $mediaType = fake()->randomElement(MediaGroup::cases());

    $media = Mockery::mock(Media::class)
        ->allows('getAttribute')
        ->with('uuid')
        ->andReturns(fake()->uuid())
        ->getMock()
        ->allows('getAttribute')
        ->with('collection_name')
        ->andReturns($mediaType->value)
        ->getMock()
        ->allows('getAttribute')
        ->with('organisation_id')
        ->andReturns($organisation->id)
        ->getMock()
        ->allows('getKey')
        ->andReturns(fake()->randomNumber())
        ->getMock();

    $pathGenerator = $this->app->get(OrganisationAwarePathGenerator::class);
    $path = $pathGenerator->getPath($media);

    expect($path)
        ->toBe(sprintf('%s/%s/%s/', $organisation->id, $mediaType->value, $media->uuid));
});

it('is able to generate a path without organisation', function (): void {
    $user = User::factory()->create();

    actingAs($user);
    $mediaType = fake()->randomElement(MediaGroup::cases());

    $media = Mockery::mock(Media::class)
        ->allows('getAttribute')
        ->with('uuid')
        ->andReturns(fake()->uuid())
        ->getMock()
        ->allows('getAttribute')
        ->with('collection_name')
        ->andReturns($mediaType->value)
        ->getMock()
        ->allows('getAttribute')
        ->with('organisation_id')
        ->andReturns(null)
        ->getMock()
        ->allows('getKey')
        ->andReturns(fake()->randomNumber())
        ->getMock();

    $pathGenerator = $this->app->get(OrganisationAwarePathGenerator::class);
    $path = $pathGenerator->getPath($media);

    expect($path)
        ->toBe(sprintf('%s/%s/', $mediaType->value, $media->uuid));
});
