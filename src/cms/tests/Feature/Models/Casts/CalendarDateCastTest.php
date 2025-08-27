<?php

declare(strict_types=1);

namespace Tests\Feature\Models\Casts;

use App\Models\Avg\AvgResponsibleProcessingRecord;

use function fake;
use function it;

it('can get', function (): void {
    $calendarDate = fake()->calendarDate();

    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create([
            'review_at' => $calendarDate,
        ]);

    $this->assertEquals($calendarDate, $avgResponsibleProcessingRecord->review_at);
});

it('can get when null', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create([
            'review_at' => null,
        ]);

    $this->assertNull($avgResponsibleProcessingRecord->review_at);
});

it('can set', function (): void {
    $calendarDate = fake()->calendarDate();

    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create([
            'review_at' => $calendarDate,
        ]);

    $this->assertDatabaseHas(AvgResponsibleProcessingRecord::class, [
        'id' => $avgResponsibleProcessingRecord->id,
        'review_at' => $calendarDate,
    ]);
});

it('can set when null', function (): void {
    AvgResponsibleProcessingRecord::factory()
        ->create([
            'review_at' => null,
        ]);

    $this->assertDatabaseHas(AvgResponsibleProcessingRecord::class, [
        'review_at' => null,
    ]);
});
