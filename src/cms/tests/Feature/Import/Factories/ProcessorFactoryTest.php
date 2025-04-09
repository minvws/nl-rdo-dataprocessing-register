<?php

declare(strict_types=1);

use App\Import\Factories\ProcessorFactory;
use App\Models\Processor;

it('skips the import when model with import_id exists', function (): void {
    $importId = fake()->importId();
    $name = fake()->word();

    $processor = Processor::factory()
        ->create([
            'import_id' => $importId,
            'name' => $name,
        ]);

    /** @var ProcessorFactory $processorFactory */
    $processorFactory = $this->app->get(ProcessorFactory::class);
    $processorFactory->create([
        'Id' => $importId, // same import_id
        'Naam' => fake()->unique()->word(), // new value for the name
    ], $processor->organisation_id);

    $processor->refresh();
    expect($processor->name)
        ->toBe($name);
});
