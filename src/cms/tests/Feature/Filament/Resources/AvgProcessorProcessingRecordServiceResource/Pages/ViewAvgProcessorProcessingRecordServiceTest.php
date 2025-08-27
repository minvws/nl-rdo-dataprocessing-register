<?php

declare(strict_types=1);

use App\Filament\Resources\AvgProcessorProcessingRecordServiceResource;
use App\Models\Avg\AvgProcessorProcessingRecordService;
use Tests\Helpers\Model\OrganisationTestHelper;

it('loads the view page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $avgProcessorProcessingRecordService = AvgProcessorProcessingRecordService::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->get(
            AvgProcessorProcessingRecordServiceResource::getUrl('view', [
                'record' => $avgProcessorProcessingRecordService,
            ]),
        )->assertSuccessful();
});
