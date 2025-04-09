<?php

declare(strict_types=1);

namespace App\Services\PublicWebsite\Content;

use App\Services\PublicWebsite\Generator;
use App\Services\PublicWebsite\PathGenerator;
use App\Services\PublicWebsite\PublicWebsiteFilesystem;
use Illuminate\Contracts\View\Factory;

class OrganisationListGenerator extends Generator
{
    public function __construct(
        private readonly PathGenerator $pathGenerator,
        private readonly PublicWebsiteFilesystem $publicWebsiteFilesystem,
        private readonly Factory $viewFactory,
    ) {
    }

    public function generate(): void
    {
        $contents = $this->viewFactory->make('public-website.organisation-list', [
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

        $this->publicWebsiteFilesystem->write($this->pathGenerator->getOrganisationIndexPath(), $contents);
    }
}
