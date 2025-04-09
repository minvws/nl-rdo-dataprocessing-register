<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Resources\WpgProcessingRecordResource;

use App\Filament\Resources\WpgProcessingRecordServiceResource;
use App\Filament\Resources\WpgProcessingRecordServiceResource\Pages\ListWpgProcessingRecordServices;
use App\Models\Wpg\WpgProcessingRecordService;

use function it;
use function Pest\Livewire\livewire;

it('loads the form', function (): void {
    $wpgProcessingRecordService = WpgProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(WpgProcessingRecordServiceResource::getUrl('edit', ['record' => $wpgProcessingRecordService]))
        ->assertSuccessful();
});

it("loads the list page", function (): void {
    $wpgProcessingRecordService = WpgProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->create();

    livewire(ListWpgProcessingRecordServices::class)
        ->assertCanSeeTableRecords([$wpgProcessingRecordService]);
});

it('loads the create page', function (): void {
    $this->get(WpgProcessingRecordServiceResource::getUrl('create'))
        ->assertSuccessful();
});
