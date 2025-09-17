<?php

declare(strict_types=1);

namespace App\Services\StaticWebsite\Content;

use App\Services\StaticWebsite\Generator;
use App\Services\StaticWebsite\PathGenerator;
use App\Services\StaticWebsite\StaticWebsiteFilesystem;
use Illuminate\Contracts\View\Factory;

class PublicWebsiteTreeListGenerator extends Generator
{
    public function __construct(
        private readonly PathGenerator $pathGenerator,
        private readonly StaticWebsiteFilesystem $staticWebsiteFilesystem,
        private readonly Factory $viewFactory,
    ) {
    }

    public function generate(): void
    {
        $contents = $this->viewFactory->make('static-website.public-website-tree-list', [
            'frontmatter' => $this->convertToFrontmatter([
                '_build' => [
                    'render' => 'never',
                    'list' => 'never',
                ],
                'cascade' => [
                    '_build' => [
                        'render' => true,
                        'list' => true,
                    ],
                ],
            ]),
        ])
            ->render();

        $this->staticWebsiteFilesystem->write($this->pathGenerator->getStaticWebsiteTreeIndexPath(), $contents);
    }
}
