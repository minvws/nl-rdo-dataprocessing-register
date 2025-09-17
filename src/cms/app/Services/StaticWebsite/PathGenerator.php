<?php

declare(strict_types=1);

namespace App\Services\StaticWebsite;

use function sprintf;

readonly class PathGenerator
{
    public function getIndexPath(): string
    {
        return '_index.html';
    }

    public function getStaticWebsiteTreePath(string $path): string
    {
        return sprintf('organisatie/%s/_index.html', $path);
    }

    public function getStaticWebsiteTreeIndexPath(): string
    {
        return 'organisatie/_index.html';
    }

    public function getStaticWebsiteTreePosterPath(string $path): string
    {
        return sprintf('organisatie/%s/poster.jpeg', $path);
    }

    public function getPublishableIndexPath(string $path): string
    {
        return sprintf('organisatie/%s/verwerkingen/_index.html', $path);
    }

    public function getPublishablePath(string $path, string $slug): string
    {
        return sprintf('organisatie/%s/verwerkingen/%s.html', $path, $slug);
    }
}
