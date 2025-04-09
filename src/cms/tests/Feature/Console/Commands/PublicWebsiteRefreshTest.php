<?php

declare(strict_types=1);

use App\Jobs\PublicWebsite\ContentGeneratorJob;
use App\Jobs\PublicWebsite\HugoWebsiteGeneratorJob;
use App\Jobs\PublicWebsite\OrganisationGeneratorJob;
use App\Jobs\PublicWebsite\PublicContentDeleteJob;
use App\Jobs\PublicWebsite\PublicWebsiteCheckJob;
use App\Jobs\PublicWebsite\PublishableListGeneratorJob;
use App\Models\Organisation;
use Illuminate\Support\Facades\Bus;

it('can run the command', function (): void {
    Bus::fake();

    $this->artisan('public-website:refresh')
        ->assertExitCode(0);

    Bus::assertChained([
        ContentGeneratorJob::class,
        HugoWebsiteGeneratorJob::class,
        PublicWebsiteCheckJob::class,
    ]);
});

it('can run the command with a published organisation', function (): void {
    Bus::fake();

    Organisation::factory()->create(['public_from' => fake()->date()]);

    $this->artisan('public-website:refresh')
        ->assertExitCode(0);

    Bus::assertChained([
        OrganisationGeneratorJob::class,
        PublishableListGeneratorJob::class,
        ContentGeneratorJob::class,
        HugoWebsiteGeneratorJob::class,
        PublicWebsiteCheckJob::class,
    ]);
});

it('can run the command with the delete parameter', function (): void {
    Bus::fake();

    $this->artisan('public-website:refresh -D')
        ->assertExitCode(0);

    Bus::assertChained([
        PublicContentDeleteJob::class,
        ContentGeneratorJob::class,
        HugoWebsiteGeneratorJob::class,
        PublicWebsiteCheckJob::class,
    ]);
});

it('can run the command with the delete parameter and a published organisation', function (): void {
    Bus::fake();

    Organisation::factory()->create(['public_from' => fake()->date()]);

    $this->artisan('public-website:refresh -D')
        ->assertExitCode(0);

    Bus::assertChained([
        PublicContentDeleteJob::class,
        OrganisationGeneratorJob::class,
        PublishableListGeneratorJob::class,
        ContentGeneratorJob::class,
        HugoWebsiteGeneratorJob::class,
        PublicWebsiteCheckJob::class,
    ]);
});
