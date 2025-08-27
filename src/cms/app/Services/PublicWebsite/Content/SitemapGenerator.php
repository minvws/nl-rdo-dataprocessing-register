<?php

declare(strict_types=1);

namespace App\Services\PublicWebsite\Content;

use App\Components\Uuid\Uuid;
use App\Services\PublicWebsite\PublicWebsiteFilesystem;
use Illuminate\Contracts\View\Factory;

class SitemapGenerator
{
    public function __construct(
        private readonly PublicWebsiteFilesystem $publicWebsiteFilesystem,
        private readonly Factory $viewFactory,
    ) {
    }

    public function generate(): void
    {
        $contents = $this->viewFactory->make('public-website.sitemap', [
            'id' => Uuid::generate()->toString(),
        ])
            ->render();

        $this->publicWebsiteFilesystem->write('sitemap.html', $contents);
    }
}
