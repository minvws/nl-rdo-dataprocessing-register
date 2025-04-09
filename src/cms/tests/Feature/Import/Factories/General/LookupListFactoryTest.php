<?php

declare(strict_types=1);

use App\Import\Factories\General\LookupListFactory;
use App\Models\Avg\AvgProcessorProcessingRecordService;

it('skips the import when model with import_id exists', function (): void {
    $name = fake()->word();

    $avgProcessorProcessingRecordService = AvgProcessorProcessingRecordService::factory()
        ->create([
            'name' => $name,
            'enabled' => false,
        ]);

    /** @var LookupListFactory $lookupListFactory */
    $lookupListFactory = $this->app->get(LookupListFactory::class);
    $lookupListFactory->create(
        AvgProcessorProcessingRecordService::class,
        $avgProcessorProcessingRecordService->organisation_id,
        $name,
    ); // should not create a new entry

    $avgProcessorProcessingRecordService->refresh();
    expect($avgProcessorProcessingRecordService->enabled)
        ->toBeFalse();
});
