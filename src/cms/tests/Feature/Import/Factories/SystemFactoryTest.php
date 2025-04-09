<?php

declare(strict_types=1);

use App\Import\Factories\SystemFactory;
use App\Models\System;

it('makes a new model on non-unique import_id exists', function (): void {
    $importId = fake()->importId();
    $description = fake()->sentence();

    $system = System::factory()
        ->create([
            'import_id' => $importId,
            'description' => $description,
        ]);

    /** @var SystemFactory $systemFactory */
    $systemFactory = $this->app->get(SystemFactory::class);
    $result = $systemFactory->create([
        'Id' => $importId, // same import_id but should be ignored
        'Omschrijving' => fake()->unique()->sentence(), // new value for the description
    ], $system->organisation_id);

    expect($result->id)->not()->toBe($system->id);
    expect($result->import_id)->toBeNull();
});

it('uses the existing model if description matched', function (): void {
    $importId = fake()->importId();
    $description = fake()->sentence();

    $system = System::factory()
        ->create([
            'import_id' => $importId,
            'description' => $description,
        ]);

    /** @var SystemFactory $systemFactory */
    $systemFactory = $this->app->get(SystemFactory::class);
    $result = $systemFactory->create([
        'Id' => $importId, // same import_id but should be ignored
        'Omschrijving' => $description, // same description
    ], $system->organisation_id);


    expect($result->id)->toBe($system->id);
});
