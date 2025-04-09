<?php

declare(strict_types=1);

use App\Filament\Resources\AvgResponsibleProcessingRecordServiceResource\Pages\ListAvgResponsibleProcessingRecordServices;
use App\Models\Avg\AvgResponsibleProcessingRecordService;

use function Pest\Livewire\livewire;

it('loads the list page', function (): void {
    $avgResponsibleProcessingRecordService = AvgResponsibleProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->count(5)
        ->create();

    livewire(ListAvgResponsibleProcessingRecordServices::class)
        ->assertCanSeeTableRecords($avgResponsibleProcessingRecordService);
});

it('loads the enabled page', function (): void {
    $enabledAvgResponsibleProcessingRecordService = AvgResponsibleProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->create(['enabled' => true]);
    $disabledAvgResponsibleProcessingRecordService = AvgResponsibleProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->create(['enabled' => false]);

    livewire(ListAvgResponsibleProcessingRecordServices::class)
        ->set('activeTab', 'enabled')
        ->assertCanSeeTableRecords([$enabledAvgResponsibleProcessingRecordService])
        ->assertCanNotSeeTableRecords([$disabledAvgResponsibleProcessingRecordService]);
});

it('loads the disabled page', function (): void {
    $enabledAvgResponsibleProcessingRecordService = AvgResponsibleProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->create(['enabled' => true]);
    $disabledAvgResponsibleProcessingRecordService = AvgResponsibleProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->create(['enabled' => false]);

    livewire(ListAvgResponsibleProcessingRecordServices::class)
        ->set('activeTab', 'disabled')
        ->assertCanSeeTableRecords([$disabledAvgResponsibleProcessingRecordService])
        ->assertCanNotSeeTableRecords([$enabledAvgResponsibleProcessingRecordService]);
});
