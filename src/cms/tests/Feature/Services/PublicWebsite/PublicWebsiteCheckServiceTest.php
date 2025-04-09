<?php

declare(strict_types=1);

use App\Models\PublicWebsiteCheck;
use App\Services\PublicWebsite\PublicSitemapCheckException;
use App\Services\PublicWebsite\PublicSitemapCheckRetryException;
use App\Services\PublicWebsite\PublicWebsiteCheckService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Mockery\MockInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

it('can check a sitemap and creates a public-website-check', function (): void {
    expect(PublicWebsiteCheck::query()->count())
        ->toBe(0);

    Http::fake([
        '*' => Http::response(json_encode([
            'date' => fake()->dateTime()->format('Y-m-d H:i:s'),
            'pages' => [
                [
                    'type' => fake()->word(),
                    'id' => fake()->uuid(),
                    'permalink' => fake()->url(),
                ],
            ],
        ])),
    ]);

    $baseUrl = fake()->url();

    $publicWebsiteCheckService = new PublicWebsiteCheckService(new NullLogger(), $baseUrl, fake()->url());
    $publicWebsiteCheckService->check();

    expect(PublicWebsiteCheck::query()->count())
        ->toBe(1);

    Http::assertSent(function (Request $request) use ($baseUrl) {
        return $request->url() === sprintf('%s/index.json', $baseUrl);
    });
});

it('will throw retry exception on connection exception', function (): void {
    Http::fake([
        '*' => Http::failedConnection(),
    ]);

    $publicWebsiteCheckService = new PublicWebsiteCheckService(new NullLogger(), fake()->domainName(), null);
    $publicWebsiteCheckService->check();
})->throws(PublicSitemapCheckRetryException::class);

it('will throw retry exception on non-ok response', function (): void {
    Http::fake([
        '*' => Http::response([], 404),
    ]);

    $publicWebsiteCheckService = new PublicWebsiteCheckService(new NullLogger(), fake()->domainName(), null);
    $publicWebsiteCheckService->check();
})->throws(PublicSitemapCheckRetryException::class);

it('will throw exception on invalid json', function (): void {
    Http::fake([
        '*' => Http::response(fake()->word()),
    ]);

    $publicWebsiteCheckService = new PublicWebsiteCheckService(new NullLogger(), fake()->domainName(), null);
    $publicWebsiteCheckService->check();
})->throws(PublicSitemapCheckException::class);

it('will throw exception on valid json but no object', function (): void {
    Http::fake([
        '*' => Http::response(json_encode(fake()->word())),
    ]);

    $publicWebsiteCheckService = new PublicWebsiteCheckService(new NullLogger(), fake()->domainName(), null);
    $publicWebsiteCheckService->check();
})->throws(PublicSitemapCheckException::class);

it('will throw exception on valid json but no valid date', function (): void {
    Http::fake([
        '*' => Http::response(json_encode(['date' => fake()->randomNumber(1)])),
    ]);

    $publicWebsiteCheckService = new PublicWebsiteCheckService(new NullLogger(), fake()->domainName(), null);
    $publicWebsiteCheckService->check();
})->throws(PublicSitemapCheckException::class);

it('will throw exception on valid json but no valid date format', function (): void {
    Http::fake([
        '*' => Http::response(json_encode(['date' => fake()->dateTime()->format('Y-m-d')])),
    ]);

    $publicWebsiteCheckService = new PublicWebsiteCheckService(new NullLogger(), fake()->domainName(), null);
    $publicWebsiteCheckService->check();
})->throws(PublicSitemapCheckException::class);

it('will skip silently (with log entry) if entry with this date already exists', function (): void {
    $date = fake()->dateTime();
    PublicWebsiteCheck::factory()->create(['build_date' => $date]);

    Http::fake([
        '*' => Http::response(json_encode(['date' => $date->format('Y-m-d H:i:s')])),
    ]);

    $logger = $this->mock(LoggerInterface::class, static function (MockInterface $mock) use ($date): void {
        $mock->expects('info');
        $mock->expects('debug')
            ->with(
                'public-website check, skipping import: entry with build_date exists',
                ['build_date' => $date->format('Y-m-d H:i:s')],
            );
    });

    $publicWebsiteCheckService = new PublicWebsiteCheckService($logger, fake()->domainName(), null);
    $publicWebsiteCheckService->check();
});
