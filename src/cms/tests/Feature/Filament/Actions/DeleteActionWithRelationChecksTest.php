<?php

declare(strict_types=1);

namespace Filament\Actions;

use App\Filament\Actions\DeleteActionWithRelationChecks;
use App\Filament\Resources\AvgProcessorProcessingRecordServiceResource\Pages\EditAvgProcessorProcessingRecordService;
use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Avg\AvgProcessorProcessingRecordService;

use function __;
use function expect;
use function it;
use function Pest\Livewire\livewire;

it('can delete the entity when there are no related records', function (): void {
    $avgProcessorProcessingRecordService = AvgProcessorProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->create();

    livewire(EditAvgProcessorProcessingRecordService::class, [
        'record' => $avgProcessorProcessingRecordService->id,
    ])
        ->callAction(DeleteActionWithRelationChecks::class)
        ->assertSuccessful()
        ->assertNotified(__('filament-actions::delete.single.notifications.deleted.title'));

    $avgProcessorProcessingRecordServiceCount = AvgProcessorProcessingRecordService::query()
        ->where(['id' => $avgProcessorProcessingRecordService->id])
        ->count();
    expect($avgProcessorProcessingRecordServiceCount)
        ->toBe(0);
});

it('can not delete the entity when there are related records', function (): void {
    $avgProcessorProcessingRecordService = AvgProcessorProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->create();
    AvgProcessorProcessingRecord::factory()
        ->create([
            'avg_processor_processing_record_service_id' => $avgProcessorProcessingRecordService->id,
        ]);

    livewire(EditAvgProcessorProcessingRecordService::class, [
        'record' => $avgProcessorProcessingRecordService->id,
    ])
        ->callAction(DeleteActionWithRelationChecks::class)
        ->assertSuccessful()
        ->assertNotified(__('general.error'));

    $avgProcessorProcessingRecordServiceCount = AvgProcessorProcessingRecordService::query()
        ->where(['id' => $avgProcessorProcessingRecordService->id])
        ->count();
    expect($avgProcessorProcessingRecordServiceCount)
        ->toBe(1);
});
