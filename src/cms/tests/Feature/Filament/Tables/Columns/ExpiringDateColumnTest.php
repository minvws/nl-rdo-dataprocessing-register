<?php

declare(strict_types=1);

use App\Filament\Resources\AvgProcessorProcessingRecordResource\Pages\ListAvgProcessorProcessingRecords;
use App\Filament\Tables\Columns\ExpiringDateColumn;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\ValueObjects\CalendarDate;
use Carbon\CarbonImmutable;

it('can make the column if the field is null', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create(['review_at' => null]);

    $this->asFilamentUser()
        ->createLivewireTestable(ListAvgProcessorProcessingRecords::class)
        ->assertTableColumnExists('review_at', static function (ExpiringDateColumn $column): bool {
            return $column->getColor($column->getState()) === null;
        }, $avgResponsibleProcessingRecord);
});

it('can make the column if the field is in the past', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create(['review_at' => CalendarDate::createFromFormat('Y-m-d', CarbonImmutable::yesterday()->format('Y-m-d'))]);

    $this->asFilamentUser()
        ->createLivewireTestable(ListAvgProcessorProcessingRecords::class)
        ->assertTableColumnExists('review_at', static function (ExpiringDateColumn $column): bool {
            return $column->getColor($column->getState()) === 'danger';
        }, $avgResponsibleProcessingRecord);
});


it('can make the column if the field is in the future', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create(['review_at' => CalendarDate::createFromFormat('Y-m-d', CarbonImmutable::tomorrow()->format('Y-m-d'))]);

    $this->asFilamentUser()
        ->createLivewireTestable(ListAvgProcessorProcessingRecords::class)
        ->assertTableColumnExists('review_at', static function (ExpiringDateColumn $column): bool {
            return $column->getColor('danger') === null;
        }, $avgResponsibleProcessingRecord);
});
