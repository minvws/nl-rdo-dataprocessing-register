<?php

declare(strict_types=1);

use App\Import\Factories\StakeholderFactory;
use App\Models\Stakeholder;

it('skips the import when model with import_id exists', function (): void {
    $importId = fake()->importId();
    $description = fake()->text();

    $stakeholder = Stakeholder::factory()
        ->create([
            'import_id' => $importId,
            'description' => $description,
        ]);

    /** @var StakeholderFactory $stakeholderFactory */
    $stakeholderFactory = $this->app->get(StakeholderFactory::class);
    $stakeholderFactory->create([
        'Id' => $importId, // same import_id
        'Omschrijving' => fake()->unique()->text(), // new value for the name
    ], $stakeholder->organisation_id);

    $stakeholder->refresh();
    expect($stakeholder->description)
        ->toBe($description);
});
