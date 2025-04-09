<?php

declare(strict_types=1);

namespace App\Services\PublicWebsite;

use App\Facades\AdminLog;
use App\Services\PublicWebsite\Content\HomeGenerator;
use App\Services\PublicWebsite\Content\OrganisationListGenerator;
use App\Services\PublicWebsite\Content\SitemapGenerator;
use Exception;

use function microtime;

class ContentGenerator
{
    public function __construct(
        private readonly HomeGenerator $homeGenerator,
        private readonly OrganisationListGenerator $organisationListGenerator,
        private readonly PublicWebsiteFilesystem $publicWebsiteFilesystem,
        private readonly SitemapGenerator $sitemapGenerator,
    ) {
    }

    public function delete(): void
    {
        AdminLog::log('Deleting content files...', [
            'startTime' => microtime(true),
        ]);

        $this->publicWebsiteFilesystem->deleteAll();
    }

    /**
     * @throws Exception
     */
    public function generate(): void
    {
        AdminLog::log('Generating content files...', [
            'startTime' => microtime(true),
        ]);

        $this->homeGenerator->generate();
        $this->sitemapGenerator->generate();
        $this->organisationListGenerator->generate();
    }
}
