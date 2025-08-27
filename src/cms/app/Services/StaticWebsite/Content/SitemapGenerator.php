<?php

declare(strict_types=1);

namespace App\Services\StaticWebsite\Content;

use App\Components\Uuid\Uuid;
use App\Services\StaticWebsite\StaticWebsiteFilesystem;
use Illuminate\Contracts\View\Factory;

class SitemapGenerator
{
    public function __construct(
        private readonly StaticWebsiteFilesystem $staticWebsiteFilesystem,
        private readonly Factory $viewFactory,
    ) {
    }

    public function generate(): void
    {
        $contents = $this->viewFactory->make('static-website.sitemap', [
            'id' => Uuid::generate()->toString(),
        ])
            ->render();

        $this->staticWebsiteFilesystem->write('sitemap.html', $contents);
    }
}
