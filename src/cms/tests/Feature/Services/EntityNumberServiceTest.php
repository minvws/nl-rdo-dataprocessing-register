<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Organisation;
use App\Services\EntityNumberService;
use Illuminate\Support\Facades\App;

use function expect;
use function it;

it('can generate a number', function (): void {
    $organisation = Organisation::factory()->create();
    $model = AvgResponsibleProcessingRecord::class;

    /** @var EntityNumberService $entityNumberService */
    $entityNumberService = App::make(EntityNumberService::class);

    $entityNumber = $entityNumberService->generate($organisation, $model);
    expect($entityNumber->number)
        ->toBeString();
});

it('logs a pdo-exception', function (): void {
    $organisation = Organisation::factory()->create();
    $model = AvgResponsibleProcessingRecord::class;

    /** @var EntityNumberService $entityNumberService */
    $entityNumberService = App::make(EntityNumberService::class);

    $entityNumber = $entityNumberService->generate($organisation, $model);
    expect($entityNumber->number)
        ->toBeString();
});
