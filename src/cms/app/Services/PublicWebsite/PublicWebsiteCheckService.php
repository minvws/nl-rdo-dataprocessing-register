<?php

declare(strict_types=1);

namespace App\Services\PublicWebsite;

use App\Jobs\PublicWebsite\PublicWebsiteCheckContentProcessorJob;
use App\Models\PublicWebsiteCheck as PublicWebsiteCheckModel;
use App\Services\JsonRequest\JsonException;
use App\Services\JsonRequest\JsonRequestService;
use App\Services\JsonRequest\RequestException;
use Carbon\CarbonImmutable;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Webmozart\Assert\Assert;

use function is_string;
use function property_exists;
use function sprintf;

class PublicWebsiteCheckService
{
    public function __construct(
        private readonly JsonRequestService $jsonRequestService,
        private readonly LoggerInterface $logger,
        private readonly string $baseUrl,
        private readonly ?string $proxy,
    ) {
    }

    /**
     * @throws PublicSitemapCheckJsonException
     * @throws PublicSitemapCheckRequestException
     */
    public function check(): void
    {
        $this->handle(false);
    }

    /**
     * @throws PublicSitemapCheckJsonException
     * @throws PublicSitemapCheckRequestException
     */
    public function checkForced(): void
    {
        $this->handle(true);
    }

    /**
     * @throws PublicSitemapCheckJsonException
     * @throws PublicSitemapCheckRequestException
     */
    private function handle(bool $force): void
    {
        $sitemapUrl = sprintf('%s/index.json', $this->baseUrl);
        $this->logger->info('running public website check', ['sitemap_url' => $sitemapUrl]);

        try {
            $sitemap = $this->jsonRequestService->makeRequest($sitemapUrl, $this->proxy);
        } catch (JsonException $jsonException) {
            throw PublicSitemapCheckJsonException::fromThrowable($jsonException);
        } catch (RequestException $requestException) {
            throw PublicSitemapCheckRequestException::fromThrowable($requestException);
        }

        $this->handleSitemap($sitemap, $force);
    }

    /**
     * @throws PublicSitemapCheckJsonException
     */
    private function handleSitemap(object $sitemapData, bool $force): void
    {
        if (!property_exists($sitemapData, 'date') || !is_string($sitemapData->date)) {
            throw new PublicSitemapCheckJsonException('date not found in sitemapData');
        }

        try {
            $sitemapBuildDate = CarbonImmutable::createFromFormat('Y-m-d H:i:s', $sitemapData->date);
            Assert::isInstanceOf($sitemapBuildDate, CarbonImmutable::class);
        } catch (InvalidArgumentException) {
            throw new PublicSitemapCheckJsonException('no valid date found in sitemapData');
        }

        $publicWebsiteCheckExists = PublicWebsiteCheckModel::where(['build_date' => $sitemapBuildDate])->exists();
        if ($publicWebsiteCheckExists === true && $force === false) {
            $this->logger->debug(
                'public-website check, skipping import: entry with build_date exists',
                ['build_date' => $sitemapBuildDate],
            );

            return;
        }

        $publicSitemapCheckProperties = [
            'build_date' => $sitemapBuildDate,
            'content' => $sitemapData,
        ];

        $this->logger->debug('importing PublicWebsiteCheck', $publicSitemapCheckProperties);
        $publicWebsiteCheck = new PublicWebsiteCheckModel($publicSitemapCheckProperties);
        $publicWebsiteCheck->save();

        PublicWebsiteCheckContentProcessorJob::dispatch($publicWebsiteCheck);
    }
}
