<?php

declare(strict_types=1);

namespace Models\Wpg;

use App\Models\Wpg\WpgGoal;
use App\Models\Wpg\WpgProcessingRecord;

use function expect;
use function fake;
use function it;

it('belongs to a WpgProcessingRecord', function (): void {
    $wpgProcessingRecord = WpgProcessingRecord::factory()->create();
    $wpgGoal = WpgGoal::factory()
        ->hasAttached($wpgProcessingRecord)
        ->create();

    expect($wpgGoal->wpgProcessingRecords->first())
        ->toBeInstanceOf(WpgProcessingRecord::class);
});

it('can retrieve the display name', function (): void {
    $description = fake()->sentence();

    $wpgGoal = WpgGoal::factory()
        ->create([
            'description' => $description,
        ]);

    expect($wpgGoal->getDisplayName())
        ->toBe($description);
});
