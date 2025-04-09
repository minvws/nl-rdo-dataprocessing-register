<?php

declare(strict_types=1);

namespace App\Services\PublicWebsite;

use App\Jobs\PublicWebsite\PublicWebsiteCheckContentProcessorJob;
use App\Models\PublicWebsiteCheck as PublicWebsiteCheckModel;
use Carbon\CarbonImmutable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Webmozart\Assert\Assert;

use function is_object;
use function is_string;
use function json_decode;
use function property_exists;
use function sprintf;

readonly class PublicWebsiteCheckService
{
    public function __construct(
        private LoggerInterface $logger,
        private string $baseUrl,
        private ?string $proxy,
    ) {
    }

    /**
     * @throws PublicSitemapCheckException
     * @throws PublicSitemapCheckRetryException
     */
    public function check(): void
    {
        $sitemapUrl = sprintf('%s/index.json', $this->baseUrl);
        $this->logger->info('running public website check', ['sitemap_url' => $sitemapUrl]);

        $response = $this->makeRequest($sitemapUrl);
        $sitemap = $this->loadSitemap($response->body());
        $this->handleSitemap($sitemap);
    }

    /**
     * @throws PublicSitemapCheckRetryException
     */
    private function makeRequest(string $url): Response
    {
        $options = [
            'timeout' => 5,
        ];

        if ($this->proxy !== null) {
            $options['proxy'] = $this->proxy;
        }

        try {
            $response = Http::withOptions($options)->get($url);
        } catch (ConnectionException $connectionException) {
            $this->logger->notice('public website check connection exception', ['exception' => $connectionException]);

            throw new PublicSitemapCheckRetryException();
        }

        if (!$response->ok()) {
            $this->logger->notice('public website check response non-ok', ['responseStatus' => $response->status()]);

            throw new PublicSitemapCheckRetryException();
        }

        return $response;
    }

    /**
     * @throws PublicSitemapCheckException
     */
    private function loadSitemap(string $responseContent): object
    {
        if (!Str::isJson($responseContent)) {
            throw new PublicSitemapCheckException('invalid json found in public website sitemap');
        }

        $sitemap = json_decode($responseContent);
        if (!is_object($sitemap)) {
            throw new PublicSitemapCheckException('invalid format found in public website sitemap');
        }

        return $sitemap;
    }

    /**
     * @throws PublicSitemapCheckException
     */
    private function handleSitemap(object $sitemapData): void
    {
        if (!property_exists($sitemapData, 'date') || !is_string($sitemapData->date)) {
            throw new PublicSitemapCheckException('date not found in sitemapData');
        }

        try {
            $sitemapBuildDate = CarbonImmutable::createFromFormat('Y-m-d H:i:s', $sitemapData->date);
            Assert::isInstanceOf($sitemapBuildDate, CarbonImmutable::class);
        } catch (InvalidArgumentException) {
            throw new PublicSitemapCheckException('no valid date found in sitemapData');
        }

        $publicWebsiteCheckExists = PublicWebsiteCheckModel::where(['build_date' => $sitemapBuildDate])->exists();
        if ($publicWebsiteCheckExists === true) {
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
