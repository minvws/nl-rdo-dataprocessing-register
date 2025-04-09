<?php

declare(strict_types=1);

namespace Tests\Feature\Models\Avg;

use App\Models\Avg\AvgGoal;
use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;

use function expect;
use function fake;
use function it;

it('has avgProcessorProcessingRecords', function (): void {
    $avgGoal = AvgGoal::factory()->create();
    $avgProcessorProcessingRecord = AvgProcessorProcessingRecord::factory()->create();
    $avgGoal->avgProcessorProcessingRecords()->attach($avgProcessorProcessingRecord);

    expect($avgGoal->avgProcessorProcessingRecords()->first())->toBeInstanceOf(AvgProcessorProcessingRecord::class)
        ->and($avgProcessorProcessingRecord->avgGoals()->first())->toBeInstanceOf(AvgGoal::class);
});

it('has avgResponsibleProcessingRecords', function (): void {
    $avgGoal = AvgGoal::factory()->create();
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()->create();
    $avgGoal->avgResponsibleProcessingRecords()->attach($avgResponsibleProcessingRecord);

    expect($avgGoal->avgResponsibleProcessingRecords()->first())->toBeInstanceOf(AvgResponsibleProcessingRecord::class)
        ->and($avgResponsibleProcessingRecord->avgGoals()->first())->toBeInstanceOf(AvgGoal::class);
});

it('can retrieve the display name', function (): void {
    $goal = fake()->sentence();

    $avgGoal = AvgGoal::factory()
        ->create([
            'goal' => $goal,
        ]);

    expect($avgGoal->getDisplayName())
        ->toBe($goal);
});
