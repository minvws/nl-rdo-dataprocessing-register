<?php

declare(strict_types=1);

use App\Models\PublicWebsiteCheck;
use App\Services\PublicWebsite\PublicSitemapCheckJsonException;
use App\Services\PublicWebsite\PublicSitemapCheckRequestException;
use App\Services\PublicWebsite\PublicWebsiteCheckService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Psr\Log\LoggerInterface;

it('can check a sitemap and creates a public-website-check', function (): void {
    expect(PublicWebsiteCheck::query()->count())
        ->toBe(0);

    httpFake([
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

    $publicWebsiteCheckService = $this->app->make(PublicWebsiteCheckService::class, [
        'baseUrl' => $baseUrl,
        'proxy' => fake()->url(),
    ]);
    $publicWebsiteCheckService->check();

    expect(PublicWebsiteCheck::query()->count())
        ->toBe(1);

    Http::assertSent(function (Request $request) use ($baseUrl) {
        return $request->url() === sprintf('%s/index.json', $baseUrl);
    });
});

it('will throw retry exception on connection exception', function (): void {
    httpFake([
        '*' => Http::failedConnection(),
    ]);

    $publicWebsiteCheckService = $this->app->make(PublicWebsiteCheckService::class, [
        'baseUrl' => fake()->domainName(),
        'proxy' => null,
    ]);
    $publicWebsiteCheckService->check();
})->throws(PublicSitemapCheckRequestException::class);

it('will throw retry exception on non-ok response', function (): void {
    httpFake([
        '*' => Http::response([], 404),
    ]);

    $publicWebsiteCheckService = $this->app->make(PublicWebsiteCheckService::class, [
        'baseUrl' => fake()->domainName(),
        'proxy' => null,
    ]);
    $publicWebsiteCheckService->check();
})->throws(PublicSitemapCheckRequestException::class);

it('will throw exception on invalid json', function (): void {
    httpFake([
        '*' => Http::response(fake()->word()),
    ]);

    $publicWebsiteCheckService = $this->app->make(PublicWebsiteCheckService::class, [
        'baseUrl' => fake()->domainName(),
        'proxy' => null,
    ]);
    $publicWebsiteCheckService->check();
})->throws(PublicSitemapCheckJsonException::class);

it('will throw exception on valid json but no object', function (): void {
    httpFake([
        '*' => Http::response(json_encode(fake()->word())),
    ]);

    $publicWebsiteCheckService = $this->app->make(PublicWebsiteCheckService::class, [
        'baseUrl' => fake()->domainName(),
        'proxy' => null,
    ]);
    $publicWebsiteCheckService->check();
})->throws(PublicSitemapCheckJsonException::class);

it('will throw exception on valid json but no valid date', function (): void {
    httpFake([
        '*' => Http::response(json_encode(['date' => fake()->randomNumber(1)])),
    ]);

    $publicWebsiteCheckService = $this->app->make(PublicWebsiteCheckService::class, [
        'baseUrl' => fake()->domainName(),
        'proxy' => null,
    ]);
    $publicWebsiteCheckService->check();
})->throws(PublicSitemapCheckJsonException::class);

it('will throw exception on valid json but no valid date format', function (): void {
    httpFake([
        '*' => Http::response(json_encode(['date' => fake()->dateTime()->format('Y-m-d')])),
    ]);

    $publicWebsiteCheckService = $this->app->make(PublicWebsiteCheckService::class, [
        'baseUrl' => fake()->domainName(),
        'proxy' => null,
    ]);
    $publicWebsiteCheckService->check();
})->throws(PublicSitemapCheckJsonException::class);

it('will skip silently (with log entry) if entry with this date already exists', function (): void {
    $date = fake()->dateTime();
    PublicWebsiteCheck::factory()->create(['build_date' => $date]);

    httpFake([
        '*' => Http::response(json_encode(['date' => $date->format('Y-m-d H:i:s')])),
    ]);

    $this->mock(LoggerInterface::class)
        ->shouldReceive('info')
        ->once()
        ->shouldReceive('debug')
        ->once()
        ->with(
            'public-website check, skipping import: entry with build_date exists',
            ['build_date' => $date->format('Y-m-d H:i:s')],
        )
        ->getMock();

    $publicWebsiteCheckService = $this->app->make(PublicWebsiteCheckService::class, [
        'baseUrl' => fake()->domainName(),
        'proxy' => null,
    ]);
    $publicWebsiteCheckService->check();
});

it('will not skip (with log entry) if entry with this date already exists but forced', function (): void {
    expect(PublicWebsiteCheck::query()->count())
        ->toBe(0);

    $date = fake()->dateTime();
    PublicWebsiteCheck::factory()->create(['build_date' => $date]);

    expect(PublicWebsiteCheck::query()->count())
        ->toBe(1);

    httpFake([
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

    $this->mock(LoggerInterface::class)
        ->shouldReceive('info')
        ->times(2)
        ->shouldReceive('debug')
        ->once()
        ->getMock();

    $publicWebsiteCheckService = $this->app->make(PublicWebsiteCheckService::class, [
        'baseUrl' => fake()->domainName(),
        'proxy' => null,
    ]);
    $publicWebsiteCheckService->checkForced();

    expect(PublicWebsiteCheck::query()->count())
        ->toBe(2);
});
