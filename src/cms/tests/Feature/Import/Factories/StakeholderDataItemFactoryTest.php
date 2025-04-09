<?php

declare(strict_types=1);

use App\Import\Factories\StakeholderDataItemFactory;
use App\Models\StakeholderDataItem;

it('skips the import when model with import_id exists', function (): void {
    $importId = fake()->importId();
    $description = fake()->text();

    $stakeholderDataItem = StakeholderDataItem::factory()
        ->create([
            'import_id' => $importId,
            'description' => $description,
        ]);

    /** @var StakeholderDataItemFactory $stakeholderDataItemFactory */
    $stakeholderDataItemFactory = $this->app->get(StakeholderDataItemFactory::class);
    $stakeholderDataItemFactory->create([
        'Id' => $importId, // same import_id
        'description' => fake()->unique()->text(), // new value for the description
    ], $stakeholderDataItem->organisation_id);

    $stakeholderDataItem->refresh();
    expect($stakeholderDataItem->description)
        ->toBe($description);
});
