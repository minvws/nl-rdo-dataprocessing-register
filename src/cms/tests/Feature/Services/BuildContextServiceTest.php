<?php

declare(strict_types=1);

use App\Services\BuildContextService;

it('is enabled by default', function (): void {
    /** @var BuildContextService $buildContextService */
    $buildContextService = $this->app->get(BuildContextService::class);

    expect($buildContextService->isBuildEnabled())
        ->toBeTrue();
});

it('can disable the build', function (): void {
    /** @var BuildContextService $buildContextService */
    $buildContextService = $this->app->get(BuildContextService::class);

    $buildContextService->disableBuild();

    expect($buildContextService->isBuildEnabled())->toBeFalse()
        ->and($buildContextService->isBuildDisabled())->toBeTrue();
});

it('can be enabled when disabled', function (): void {
    /** @var BuildContextService $buildContextService */
    $buildContextService = $this->app->get(BuildContextService::class);

    $buildContextService->disableBuild();
    $buildContextService->enableBuild();

    expect($buildContextService->isBuildEnabled())->toBeTrue()
        ->and($buildContextService->isBuildDisabled())->toBeFalse();
});
