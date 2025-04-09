<?php

declare(strict_types=1);

use App\Filament\Resources\AvgProcessorProcessingRecordResource\Pages\ListAvgProcessorProcessingRecords;
use App\Filament\Tables\Columns\ExpiringDateColumn;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use Carbon\CarbonImmutable;

use function Pest\Livewire\livewire;

it('can make the column if the field is null', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create(['review_at' => null]);

    livewire(ListAvgProcessorProcessingRecords::class)
        ->assertTableColumnExists('review_at', static function (ExpiringDateColumn $column): bool {
            return $column->getColor($column->getState()) === null;
        }, $avgResponsibleProcessingRecord);
});

it('can make the column if the field is in the past', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create(['review_at' => CarbonImmutable::yesterday()]);

    livewire(ListAvgProcessorProcessingRecords::class)
        ->assertTableColumnExists('review_at', static function (ExpiringDateColumn $column): bool {
            return $column->getColor($column->getState()) === 'danger';
        }, $avgResponsibleProcessingRecord);
});


it('can make the column if the field is in the future', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create(['review_at' => CarbonImmutable::tomorrow()]);

    livewire(ListAvgProcessorProcessingRecords::class)
        ->assertTableColumnExists('review_at', static function (ExpiringDateColumn $column): bool {
            return $column->getColor('danger') === null;
        }, $avgResponsibleProcessingRecord);
});
