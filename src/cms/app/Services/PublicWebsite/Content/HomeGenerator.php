<?php

declare(strict_types=1);

namespace App\Services\PublicWebsite\Content;

use App\Repositories\PublicWebsiteRepository;
use App\Services\PublicWebsite\Generator;
use App\Services\PublicWebsite\PathGenerator;
use App\Services\PublicWebsite\PublicWebsiteFilesystem;
use Illuminate\Contracts\View\Factory;

use function __;

class HomeGenerator extends Generator
{
    public function __construct(
        private readonly PathGenerator $pathGenerator,
        private readonly PublicWebsiteFilesystem $publicWebsiteFilesystem,
        private readonly PublicWebsiteRepository $publicWebsiteRepository,
        private readonly Factory $viewFactory,
    ) {
    }

    public function generate(): void
    {
        $publicWebsite = $this->publicWebsiteRepository->get();

        $contents = $this->viewFactory->make('public-website.home', [
            'frontmatter' => $this->convertToFrontmatter([
                'id' => $publicWebsite->id,
                'title' => __('public_website.content.home_title'),
            ]),
            'content' => $this->convertMarkdownToHtml($publicWebsite->home_content),
        ])->render();

        $this->publicWebsiteFilesystem->write($this->pathGenerator->getIndexPath(), $contents);
    }
}
