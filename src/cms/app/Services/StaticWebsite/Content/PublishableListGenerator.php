<?php

declare(strict_types=1);

namespace App\Services\StaticWebsite\Content;

use App\Models\PublicWebsiteTree;
use App\Services\OrganisationPublishableRecordsService;
use App\Services\StaticWebsite\Generator;
use App\Services\StaticWebsite\PathGenerator;
use App\Services\StaticWebsite\StaticWebsiteFilesystem;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Arr;

use function __;
use function array_merge;

class PublishableListGenerator extends Generator
{
    public function __construct(
        private readonly OrganisationPublishableRecordsService $organisationPublishableRecordsService,
        private readonly PathGenerator $pathGenerator,
        private readonly StaticWebsiteFilesystem $staticWebsiteFilesystem,
        private readonly Factory $viewFactory,
    ) {
    }

    /**
     * @param array<string> $parentSlugs
     */
    public function generate(PublicWebsiteTree $publicWebsiteTree, array $parentSlugs): void
    {
        $organisation = $publicWebsiteTree->organisation;
        if ($organisation === null) {
            return;
        }

        $publishableRecords = $this->organisationPublishableRecordsService->getPublishableRecords($organisation);

        if ($publishableRecords->isEmpty()) {
            return;
        }

        $listMarkdown = $this->viewFactory->make('static-website.processing-record-list', [
            'title' => __('processing_record.model_plural'),
        ])->render();

        $path = Arr::join(array_merge($parentSlugs, [$publicWebsiteTree->slug]), '/');
        $this->staticWebsiteFilesystem->write($this->pathGenerator->getPublishableIndexPath($path), $listMarkdown);
    }
}
