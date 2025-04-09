<?php

declare(strict_types=1);

use App\Filament\Resources\DataBreachRecordResource;
use App\Models\DataBreachRecord;

it('can load the view page', function (): void {
    $dataBreachRecord = DataBreachRecord::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(DataBreachRecordResource::getUrl('view', ['record' => $dataBreachRecord->id]))
        ->assertSuccessful();
});
