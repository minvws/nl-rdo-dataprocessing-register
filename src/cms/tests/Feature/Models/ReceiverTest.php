<?php

declare(strict_types=1);

use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Receiver;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertTrue;

it('can belong to a AvgResponsibleProcessingRecord', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()->create();
    $receiver = Receiver::factory()->create();

    $receiver->avgResponsibleProcessingRecords()->attach($avgResponsibleProcessingRecord);

    assertTrue($receiver->avgResponsibleProcessingRecords()->exists());
    assertCount(1, $receiver->avgResponsibleProcessingRecords);
    expect($receiver->avgResponsibleProcessingRecords->first()->id)
        ->toBe($avgResponsibleProcessingRecord->id);
});

it('can retrieve the display name', function (): void {
    $description = fake()->sentence();

    $receiver = Receiver::factory()
        ->create([
            'description' => $description,
        ]);

    expect($receiver->getDisplayName())
        ->toBe($description);
});

it('returns the default when no description', function (): void {
    $receiver = Receiver::factory()
        ->create([
            'description' => null,
        ]);

    expect($receiver->getDisplayName())
        ->toBe('—');
});
