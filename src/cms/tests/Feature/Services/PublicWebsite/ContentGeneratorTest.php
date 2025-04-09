<?php

declare(strict_types=1);

use App\Services\PublicWebsite\Content\HomeGenerator;
use App\Services\PublicWebsite\Content\OrganisationListGenerator;
use App\Services\PublicWebsite\Content\SitemapGenerator;
use App\Services\PublicWebsite\ContentGenerator;
use App\Services\PublicWebsite\PublicWebsiteFilesystem;

it('will write to the filesystem on delete', function (): void {
    $publicWebsiteFilesystem = $this->createMock(PublicWebsiteFilesystem::class);
    $publicWebsiteFilesystem->expects($this->once())
        ->method('deleteAll');

    /** @var ContentGenerator $contentGenerator */
    $contentGenerator = $this->app->make(ContentGenerator::class, ['publicWebsiteFilesystem' => $publicWebsiteFilesystem]);
    $contentGenerator->delete();
});

it('will call the correct generators on generate', function (): void {
    $homeGenerator = $this->createMock(HomeGenerator::class);
    $homeGenerator->expects($this->once())
        ->method('generate');
    $organisationListGenerator = $this->createMock(OrganisationListGenerator::class);
    $organisationListGenerator->expects($this->once())
        ->method('generate');
    $sitemapGenerator = $this->createMock(SitemapGenerator::class);
    $sitemapGenerator->expects($this->once())
        ->method('generate');

    /** @var ContentGenerator $contentGenerator */
    $contentGenerator = $this->app->make(ContentGenerator::class, [
        'homeGenerator' => $homeGenerator,
        'organisationListGenerator' => $organisationListGenerator,
        'sitemapGenerator' => $sitemapGenerator,
    ]);
    $contentGenerator->generate();
});
