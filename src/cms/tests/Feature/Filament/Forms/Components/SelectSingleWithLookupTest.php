<?php

declare(strict_types=1);

use App\Filament\Forms\Components\Select\SelectSingleWithLookup;
use App\Models\Avg\AvgProcessorProcessingRecordService;
use Tests\Helpers\Model\OrganisationTestHelper;

it('can make the select input', function (): void {
    $organisation = OrganisationTestHelper::create();

    AvgProcessorProcessingRecordService::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation);
    $selectsingleWithLookupField = SelectSingleWithLookup::makeWithDisabledOptions(
        'name',
        'avgProcessorProcessingRecordService',
        AvgProcessorProcessingRecordService::class,
        'name',
    );

    expect($selectsingleWithLookupField)
        ->toBeInstanceOf(SelectSingleWithLookup::class);
});
