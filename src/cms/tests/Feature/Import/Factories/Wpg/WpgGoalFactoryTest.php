<?php

declare(strict_types=1);

use App\Import\Factories\Wpg\WpgGoalFactory;
use App\Models\Wpg\WpgGoal;

it('skips the import when model with import_id exists', function (): void {
    $importId = fake()->importId();
    $description = fake()->sentence();

    $wpgGoal = WpgGoal::factory()
        ->create([
            'import_id' => $importId,
            'description' => $description,
        ]);

    /** @var WpgGoalFactory $wpgGoalFactory */
    $wpgGoalFactory = $this->app->get(WpgGoalFactory::class);
    $wpgGoalFactory->create([
        'Id' => $importId, // same import_id
        'Omschrijving' => fake()->unique()->sentence(), // new value for the description
    ], $wpgGoal->organisation_id);

    $wpgGoal->refresh();
    expect($wpgGoal->description)
        ->toBe($description);
});
