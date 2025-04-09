<?php

declare(strict_types=1);

use App\Import\Factories\ContactPersonFactory;
use App\Models\ContactPerson;

it('skips the import when model with import_id exists', function (): void {
    $importId = fake()->importId();
    $name = fake()->name();

    $contactPerson = ContactPerson::factory()
        ->create([
            'import_id' => $importId,
            'name' => $name,
        ]);

    /** @var ContactPersonFactory $contactPersonFactory */
    $contactPersonFactory = $this->app->get(ContactPersonFactory::class);
    $contactPersonFactory->create([
        'Id' => $importId, // same import_id
        'Naam' => fake()->unique()->name(), // new value for the name
    ], $contactPerson->organisation_id);

    $contactPerson->refresh();
    expect($contactPerson->name)
        ->toBe($name);
});
