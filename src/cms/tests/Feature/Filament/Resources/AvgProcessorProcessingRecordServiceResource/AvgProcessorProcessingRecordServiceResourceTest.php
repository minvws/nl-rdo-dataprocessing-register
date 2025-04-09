<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Resources\AvgProcessorProcessingRecordResource;

use App\Filament\Resources\AvgProcessorProcessingRecordServiceResource;
use App\Filament\Resources\AvgProcessorProcessingRecordServiceResource\Pages\ListAvgProcessorProcessingRecordServices;
use App\Models\Avg\AvgProcessorProcessingRecordService;

use function it;
use function Pest\Livewire\livewire;

it('loads the form', function (): void {
    $avgProcessorProcessingRecordService = AvgProcessorProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(AvgProcessorProcessingRecordServiceResource::getUrl('edit', ['record' => $avgProcessorProcessingRecordService]))
        ->assertSuccessful();
});

it("loads the list page", function (): void {
    $avgProcessorProcessingRecordService = AvgProcessorProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->create();

    livewire(ListAvgProcessorProcessingRecordServices::class)
        ->assertCanSeeTableRecords([$avgProcessorProcessingRecordService]);
});

it('loads the create page', function (): void {
    $this->get(AvgProcessorProcessingRecordServiceResource::getUrl('create'))
        ->assertSuccessful();
});
