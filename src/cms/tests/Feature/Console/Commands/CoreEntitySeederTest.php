<?php

declare(strict_types=1);

use App\Models\Organisation;

it('can seed data', function (): void {
    $organisation = Organisation::factory()->create();
    $amount = fake()->numberBetween(1, 3);

    $this->artisan('app:core-entity-seeder')
        ->expectsQuestion('Organisation', $organisation->id)
        ->expectsQuestion('Amount of entities', $amount)
        ->assertSuccessful();

    $totalCount = $organisation->algorithmRecords->count()
        + $organisation->avgProcessorProcessingRecords->count()
        + $organisation->avgResponsibleProcessingRecords->count()
        + $organisation->wpgProcessingRecords->count();

    expect($totalCount)
        ->toBe($amount);
});
