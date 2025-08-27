<?php

declare(strict_types=1);

use App\Enums\Media\MediaGroup;
use App\Models\Organisation;
use App\Models\PublicWebsiteTree;
use App\Services\StaticWebsite\Content\PublicWebsiteTreeNodeGenerator;
use App\Services\StaticWebsite\StaticWebsiteFilesystem;
use App\Vendor\MediaLibrary\Media;
use Carbon\CarbonImmutable;

it('will write the output if no organisation linked', function (): void {
    $publicWebsiteTree = PublicWebsiteTree::factory()
        ->createQuietly([
            'organisation_id' => null,
            'public_from' => fake()->date(),
        ]);

    $this->mock(StaticWebsiteFilesystem::class)
        ->shouldReceive('write')
        ->once()
        ->withSomeOfArgs(sprintf('organisatie/%s/_index.html', $publicWebsiteTree->slug));

    $publicWebsiteTreeNodeGenerator = $this->app->get(PublicWebsiteTreeNodeGenerator::class);
    $publicWebsiteTreeNodeGenerator->generate($publicWebsiteTree, []);
});

it('will write the output if no organisation linked but with parent', function (): void {
    $parentSlug = fake()->slug();
    $publicWebsiteTree = PublicWebsiteTree::factory()
        ->createQuietly([
            'organisation_id' => null,
            'public_from' => fake()->date(),
        ]);

    $this->mock(StaticWebsiteFilesystem::class)
        ->shouldReceive('write')
        ->once()
        ->withSomeOfArgs(sprintf('organisatie/%s/%s/_index.html', $parentSlug, $publicWebsiteTree->slug));

    $publicWebsiteTreeNodeGenerator = $this->app->get(PublicWebsiteTreeNodeGenerator::class);
    $publicWebsiteTreeNodeGenerator->generate($publicWebsiteTree, [$parentSlug]);
});

it('will update the published_at when generated', function (): void {
    $publishedAt = CarbonImmutable::yesterday();
    $organisation = Organisation::factory()
        ->createQuietly([
            'published_at' => $publishedAt,
        ]);
    $publicWebsiteTree = PublicWebsiteTree::factory()
        ->recycle($organisation)
        ->create(['public_from' => fake()->date()]);

    /** @var PublicWebsiteTreeNodeGenerator $publicWebsiteTreeNodeGenerator */
    $publicWebsiteTreeNodeGenerator = $this->app->get(PublicWebsiteTreeNodeGenerator::class);
    $publicWebsiteTreeNodeGenerator->generate($publicWebsiteTree, []);

    $organisation->refresh();
    expect($organisation->published_at->toJSON())
        ->toBe($publishedAt->toJSON());
});

it('will copy the poster', function (): void {
    $this->mock(StaticWebsiteFilesystem::class)
        ->shouldReceive('deleteAll')
        ->never()
        ->shouldReceive('writeStream')
        ->once()
        ->shouldReceive('write')
        ->once();

    $publicWebsiteTree = PublicWebsiteTree::factory()
        ->withPosterImage()
        ->createQuietly([
            'public_from' => fake()->date(),
        ]);

    $publicWebsiteTreeNodeGenerator = $this->app->get(PublicWebsiteTreeNodeGenerator::class);
    $publicWebsiteTreeNodeGenerator->generate($publicWebsiteTree, []);
});

it('will not fail if poster copy is skipped', function (): void {
    $this->mock(StaticWebsiteFilesystem::class)
        ->shouldReceive('write')
        ->once()
        ->shouldReceive('writeStream')
        ->never();

    $publicWebsiteTree = PublicWebsiteTree::factory()
        ->createQuietly([
            'public_from' => fake()->date(),
        ]);
    Media::factory()
        ->createQuietly([
            'model_id' => $publicWebsiteTree->id,
            'model_type' => $publicWebsiteTree::class,
            'collection_name' => MediaGroup::PUBLIC_WEBSITE_TREE->value,
        ]);

    $publicWebsiteTreeNodeGenerator = $this->app->get(PublicWebsiteTreeNodeGenerator::class);
    $publicWebsiteTreeNodeGenerator->generate($publicWebsiteTree, []);
});
