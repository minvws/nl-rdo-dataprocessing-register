<?php

declare(strict_types=1);

namespace App\Services\StaticWebsite\Content;

use App\Repositories\PublicWebsiteRepository;
use App\Services\StaticWebsite\Generator;
use App\Services\StaticWebsite\PathGenerator;
use App\Services\StaticWebsite\StaticWebsiteFilesystem;
use Illuminate\Contracts\View\Factory;
use JsonException;

use function __;

class HomeGenerator extends Generator
{
    public function __construct(
        private readonly PathGenerator $pathGenerator,
        private readonly StaticWebsiteFilesystem $staticWebsiteFilesystem,
        private readonly PublicWebsiteRepository $publicWebsiteRepository,
        private readonly Factory $viewFactory,
    ) {
    }

    /**
     * @throws JsonException
     */
    public function generate(): void
    {
        $publicWebsite = $this->publicWebsiteRepository->get();

        $contents = $this->viewFactory->make('static-website.home', [
            'frontmatter' => $this->convertToFrontmatter([
                'id' => $publicWebsite->id->toString(),
                'title' => __('static_website.content.home_title'),
            ]),
            'content' => $this->convertMarkdownToHtml($publicWebsite->home_content),
        ])->render();

        $this->staticWebsiteFilesystem->write($this->pathGenerator->getIndexPath(), $contents);
    }
}
