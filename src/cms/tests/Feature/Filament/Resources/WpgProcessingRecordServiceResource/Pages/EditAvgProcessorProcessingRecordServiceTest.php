<?php

declare(strict_types=1);

use App\Filament\Resources\WpgProcessingRecordServiceResource;
use App\Models\Wpg\WpgProcessingRecordService;

it('loads the edit page', function (): void {
    $wpgProcessingRecordService = WpgProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(
        WpgProcessingRecordServiceResource::getUrl('edit', ['record' => $wpgProcessingRecordService->id]),
    )->assertSuccessful();
});
