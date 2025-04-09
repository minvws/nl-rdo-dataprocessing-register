<?php

declare(strict_types=1);

use App\Import\Factories\ReceiverFactory;
use App\Models\Receiver;

it('skips the import when model with import_id exists', function (): void {
    $importId = fake()->importId();
    $description = fake()->sentence();

    $receiver = Receiver::factory()
        ->create([
            'import_id' => $importId,
            'description' => $description,
        ]);

    /** @var ReceiverFactory $receiverFactory */
    $receiverFactory = $this->app->get(ReceiverFactory::class);
    $receiverFactory->create([
        'Id' => $importId, // same import_id
        'Omschrijving' => fake()->unique()->sentence(), // new value for the description
    ], $receiver->organisation_id);

    $receiver->refresh();
    expect($receiver->description)
        ->toBe($description);
});
