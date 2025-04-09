<?php

declare(strict_types=1);

use App\Filament\Resources\WpgProcessingRecordServiceResource\Pages\ListWpgProcessingRecordServices;
use App\Models\Wpg\WpgProcessingRecordService;

use function Pest\Livewire\livewire;

it('loads the list page', function (): void {
    $wpgProcessingRecordService = WpgProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->count(5)
        ->create();

    livewire(ListWpgProcessingRecordServices::class)
        ->assertCanSeeTableRecords($wpgProcessingRecordService);
});

it('loads the enabled page', function (): void {
    $enabledWpgProcessingRecordService = WpgProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->create(['enabled' => true]);
    $disabledWpgProcessingRecordService = WpgProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->create(['enabled' => false]);

    livewire(ListWpgProcessingRecordServices::class)
        ->set('activeTab', 'enabled')
        ->assertCanSeeTableRecords([$enabledWpgProcessingRecordService])
        ->assertCanNotSeeTableRecords([$disabledWpgProcessingRecordService]);
});

it('loads the disabled page', function (): void {
    $enabledWpgProcessingRecordService = WpgProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->create(['enabled' => true]);
    $disabledWpgProcessingRecordService = WpgProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->create(['enabled' => false]);

    livewire(ListWpgProcessingRecordServices::class)
        ->set('activeTab', 'disabled')
        ->assertCanSeeTableRecords([$disabledWpgProcessingRecordService])
        ->assertCanNotSeeTableRecords([$enabledWpgProcessingRecordService]);
});
