<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Filament\Forms\Components\Select\SelectSingleWithLookup;
use App\Models\Avg\AvgProcessorProcessingRecordService;

it('can make the select input', function (): void {
    $this->user->assignGlobalRole(Role::FUNCTIONAL_MANAGER);

    AvgProcessorProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->create();
    $selectsingleWithLookupField = SelectSingleWithLookup::makeWithDisabledOptions(
        'name',
        'avgProcessorProcessingRecordService',
        AvgProcessorProcessingRecordService::class,
        'name',
    );

    expect($selectsingleWithLookupField)
        ->toBeInstanceOf(SelectSingleWithLookup::class);
});
