<?php

declare(strict_types=1);

namespace App\Services\PublicWebsite\Content;

use App\Models\Organisation;
use App\Services\OrganisationPublishableRecordsService;
use App\Services\PublicWebsite\Generator;
use App\Services\PublicWebsite\PathGenerator;
use App\Services\PublicWebsite\PublicWebsiteFilesystem;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\Factory;
use JsonException;

use function is_resource;

class OrganisationGenerator extends Generator
{
    public function __construct(
        private readonly OrganisationPublishableRecordsService $organisationPublishableRecordsService,
        private readonly PathGenerator $pathGenerator,
        private readonly PublicWebsiteFilesystem $publicWebsiteFilesystem,
        private readonly PublishableGenerator $publishableGenerator,
        private readonly Factory $viewFactory,
    ) {
    }

    /**
     * @throws JsonException
     */
    public function generate(Organisation $organisation): void
    {
        $this->copyPoster($organisation);

        $contents = $this->viewFactory->make('public-website.organisation', [
            'frontmatter' => $this->convertToFrontmatter(['title' => $organisation->name, 'type' => 'organisation']),
            'content' => $this->convertMarkdownToHtml($organisation->public_website_content),
            'organisation' => $organisation,
        ])->render();

        $this->publicWebsiteFilesystem->write($this->pathGenerator->getOrganisationPath($organisation), $contents);

        if ($organisation->published_at === null) {
            $organisation->published_at = CarbonImmutable::now();
            $organisation->saveQuietly(); // only storing the publisedAt-value here, don't generate new events
        }

        $publishableRecords = $this->organisationPublishableRecordsService->getPublishableRecords($organisation);
        foreach ($publishableRecords as $publishableRecord) {
            $this->publishableGenerator->generate($publishableRecord);
        }
    }

    private function copyPoster(Organisation $organisation): void
    {
        $posterImage = $organisation->getFilamentPoster();
        if ($posterImage === null) {
            return;
        }

        $posterImageStream = $posterImage->stream();
        if (!is_resource($posterImageStream)) {
            return;
        }

        $this->publicWebsiteFilesystem->writeStream($this->pathGenerator->getOrganisationPosterPath($organisation), $posterImageStream);
    }
}
