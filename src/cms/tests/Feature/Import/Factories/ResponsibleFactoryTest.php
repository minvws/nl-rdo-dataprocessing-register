<?php

declare(strict_types=1);

use App\Import\Factories\ResponsibleFactory;
use App\Models\Responsible;

it('skips the import when model with import_id exists', function (): void {
    $importId = fake()->importId();
    $name = fake()->sentence();

    $responsible = Responsible::factory()
        ->create([
            'import_id' => $importId,
            'name' => $name,
        ]);

    /** @var ResponsibleFactory $responsibleFactory */
    $responsibleFactory = $this->app->get(ResponsibleFactory::class);
    $responsibleFactory->create([
        'Id' => $importId, // same import_id
        'Omschrijving' => fake()->unique()->sentence(), // new value for the name
    ], $responsible->organisation_id);

    $responsible->refresh();
    expect($responsible->name)
        ->toBe($name);
});
