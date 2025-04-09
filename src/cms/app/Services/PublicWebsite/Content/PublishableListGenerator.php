<?php

declare(strict_types=1);

namespace App\Services\PublicWebsite\Content;

use App\Models\Organisation;
use App\Services\OrganisationPublishableRecordsService;
use App\Services\PublicWebsite\Generator;
use App\Services\PublicWebsite\PathGenerator;
use App\Services\PublicWebsite\PublicWebsiteFilesystem;
use Illuminate\Contracts\View\Factory;

use function __;

class PublishableListGenerator extends Generator
{
    public function __construct(
        private readonly OrganisationPublishableRecordsService $organisationPublishableRecordsService,
        private readonly PathGenerator $pathGenerator,
        private readonly PublicWebsiteFilesystem $publicWebsiteFilesystem,
        private readonly Factory $viewFactory,
    ) {
    }

    public function generate(Organisation $organisation): void
    {
        $publishableRecords = $this->organisationPublishableRecordsService->getPublishableRecords($organisation);

        if ($publishableRecords->isEmpty()) {
            return;
        }

        $listMarkdown = $this->viewFactory->make('public-website.processing-record-list', [
            'title' => __('processing_record.model_plural'),
        ])->render();

        $this->publicWebsiteFilesystem->write($this->pathGenerator->getPublishableIndexPath($organisation), $listMarkdown);
    }
}
