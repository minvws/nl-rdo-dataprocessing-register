<?php

declare(strict_types=1);

namespace App\Services\StaticWebsite;

use App\Jobs\StaticWebsite\StaticWebsiteCheckContentProcessorJob;
use App\Models\StaticWebsiteCheck;
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

class StaticWebsiteCheckService
{
    public function __construct(
        private readonly JsonRequestService $jsonRequestService,
        private readonly LoggerInterface $logger,
        private readonly string $baseUrl,
        private readonly ?string $proxy,
    ) {
    }

    /**
     * @throws StaticSitemapCheckJsonException
     * @throws StaticSitemapCheckRequestException
     */
    public function check(): void
    {
        $this->handle(false);
    }

    /**
     * @throws StaticSitemapCheckJsonException
     * @throws StaticSitemapCheckRequestException
     */
    public function checkForced(): void
    {
        $this->handle(true);
    }

    /**
     * @throws StaticSitemapCheckJsonException
     * @throws StaticSitemapCheckRequestException
     */
    private function handle(bool $force): void
    {
        $sitemapUrl = sprintf('%s/index.json', $this->baseUrl);
        $this->logger->info('running static website check', ['sitemap_url' => $sitemapUrl]);

        try {
            $sitemap = $this->jsonRequestService->makeRequest($sitemapUrl, $this->proxy);
        } catch (JsonException $jsonException) {
            throw StaticSitemapCheckJsonException::fromThrowable($jsonException);
        } catch (RequestException $requestException) {
            throw StaticSitemapCheckRequestException::fromThrowable($requestException);
        }

        $this->handleSitemap($sitemap, $force);
    }

    /**
     * @throws StaticSitemapCheckJsonException
     */
    private function handleSitemap(object $sitemapData, bool $force): void
    {
        if (!property_exists($sitemapData, 'date') || !is_string($sitemapData->date)) {
            throw new StaticSitemapCheckJsonException('date not found in sitemapData');
        }

        try {
            $sitemapBuildDate = CarbonImmutable::createFromFormat('Y-m-d H:i:s', $sitemapData->date);
            Assert::isInstanceOf($sitemapBuildDate, CarbonImmutable::class);
        } catch (InvalidArgumentException) {
            throw new StaticSitemapCheckJsonException('no valid date found in sitemapData');
        }

        $staticWebsiteCheckExists = StaticWebsiteCheck::where(['build_date' => $sitemapBuildDate])->exists();
        if ($staticWebsiteCheckExists === true && $force === false) {
            $this->logger->debug(
                'static-website check, skipping import: entry with build_date exists',
                ['build_date' => $sitemapBuildDate],
            );

            return;
        }

        $staticWebsiteSitemapCheckProperties = [
            'build_date' => $sitemapBuildDate,
            'content' => $sitemapData,
        ];

        $this->logger->debug('importing StaticWebsiteCheck', $staticWebsiteSitemapCheckProperties);
        $staticWebsiteCheck = new StaticWebsiteCheck($staticWebsiteSitemapCheckProperties);
        $staticWebsiteCheck->save();

        StaticWebsiteCheckContentProcessorJob::dispatch($staticWebsiteCheck);
    }
}
