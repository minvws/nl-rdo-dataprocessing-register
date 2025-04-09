<?php

declare(strict_types=1);

use App\Filament\Resources\WpgProcessingRecordResource;
use App\Models\Wpg\WpgProcessingRecord;

it('can load the ViewWpgProcessingRecord page', function (): void {
    $wpgProcessingRecord = WpgProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(WpgProcessingRecordResource::getUrl('view', ['record' => $wpgProcessingRecord->id]))
        ->assertSuccessful();
});
