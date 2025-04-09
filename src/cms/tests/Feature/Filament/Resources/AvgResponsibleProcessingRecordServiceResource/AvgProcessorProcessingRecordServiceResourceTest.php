<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Resources\AvgProcessorProcessingRecordResource;

use App\Filament\Resources\AvgResponsibleProcessingRecordServiceResource;
use App\Filament\Resources\AvgResponsibleProcessingRecordServiceResource\Pages\ListAvgResponsibleProcessingRecordServices;
use App\Models\Avg\AvgResponsibleProcessingRecordService;

use function it;
use function Pest\Livewire\livewire;

it('loads the form', function (): void {
    $avgResponsibleProcessingRecordService = AvgResponsibleProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(AvgResponsibleProcessingRecordServiceResource::getUrl('edit', ['record' => $avgResponsibleProcessingRecordService]))
        ->assertSuccessful();
});

it("loads the list page", function (): void {
    $avgResponsibleProcessingRecordService = AvgResponsibleProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->create();

    livewire(ListAvgResponsibleProcessingRecordServices::class)
        ->assertCanSeeTableRecords([$avgResponsibleProcessingRecordService]);
});

it('loads the create page', function (): void {
    $this->get(AvgResponsibleProcessingRecordServiceResource::getUrl('create'))
        ->assertSuccessful();
});
