<?php

declare(strict_types=1);

namespace App\Services\PublicWebsite;

use App\Facades\AdminLog;
use App\Models\Organisation;
use App\Services\OrganisationPublishableRecordsService;
use App\Services\PublicWebsite\Content\HomeGenerator;
use App\Services\PublicWebsite\Content\OrganisationGenerator;
use App\Services\PublicWebsite\Content\OrganisationListGenerator;
use App\Services\PublicWebsite\Content\PublishableGenerator;
use App\Services\PublicWebsite\Content\PublishableListGenerator;
use App\Services\PublicWebsite\Content\SitemapGenerator;
use Carbon\CarbonImmutable;
use Exception;

use function microtime;

class ContentGenerator
{
    public function __construct(
        private readonly HomeGenerator $homeGenerator,
        private readonly OrganisationGenerator $organisationGenerator,
        private readonly OrganisationListGenerator $organisationListGenerator,
        private readonly OrganisationPublishableRecordsService $organisationPublishableRecordsService,
        private readonly PublicWebsiteFilesystem $publicWebsiteFilesystem,
        private readonly PublishableGenerator $publishableGenerator,
        private readonly PublishableListGenerator $publishableListGenerator,
        private readonly SitemapGenerator $sitemapGenerator,
    ) {
    }

    /**
     * @throws Exception
     */
    public function generate(): void
    {
        AdminLog::log('Generating content files...', [
            'startTime' => microtime(true),
        ]);

        $this->publicWebsiteFilesystem->deleteAll();

        $organisations = Organisation::where('public_from', '<=', CarbonImmutable::now())->get();
        foreach ($organisations as $organisation) {
            $this->organisationGenerator->generate($organisation);

            $publishableRecords = $this->organisationPublishableRecordsService->getPublishableRecords($organisation);
            foreach ($publishableRecords as $publishableRecord) {
                $this->publishableGenerator->generate($publishableRecord);
            }

            $this->publishableListGenerator->generate($organisation);
        }

        $this->homeGenerator->generate();
        $this->sitemapGenerator->generate();
        $this->organisationListGenerator->generate();
    }
}
