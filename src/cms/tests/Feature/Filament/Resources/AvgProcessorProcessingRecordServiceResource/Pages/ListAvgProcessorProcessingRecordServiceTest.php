<?php

declare(strict_types=1);

use App\Filament\Resources\AvgProcessorProcessingRecordServiceResource\Pages\ListAvgProcessorProcessingRecordServices;
use App\Models\Avg\AvgProcessorProcessingRecordService;

use function Pest\Livewire\livewire;

it('loads the list page', function (): void {
    $avgProcessorProcessingRecordService = AvgProcessorProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->count(5)
        ->create();

    livewire(ListAvgProcessorProcessingRecordServices::class)
        ->assertCanSeeTableRecords($avgProcessorProcessingRecordService);
});

it('loads the enabled page', function (): void {
    $enabledAvgProcessorProcessingRecordService = AvgProcessorProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->create(['enabled' => true]);
    $disabledAvgProcessorProcessingRecordService = AvgProcessorProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->create(['enabled' => false]);

    livewire(ListAvgProcessorProcessingRecordServices::class)
        ->set('activeTab', 'enabled')
        ->assertCanSeeTableRecords([$enabledAvgProcessorProcessingRecordService])
        ->assertCanNotSeeTableRecords([$disabledAvgProcessorProcessingRecordService]);
});

it('loads the disabled page', function (): void {
    $enabledAvgProcessorProcessingRecordService = AvgProcessorProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->create(['enabled' => true]);
    $disabledAvgProcessorProcessingRecordService = AvgProcessorProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->create(['enabled' => false]);

    livewire(ListAvgProcessorProcessingRecordServices::class)
        ->set('activeTab', 'disabled')
        ->assertCanSeeTableRecords([$disabledAvgProcessorProcessingRecordService])
        ->assertCanNotSeeTableRecords([$enabledAvgProcessorProcessingRecordService]);
});
