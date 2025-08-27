<?php

declare(strict_types=1);

use App\Filament\Resources\AvgProcessorProcessingRecordServiceResource;
use App\Models\Avg\AvgProcessorProcessingRecordService;
use Tests\Helpers\Model\OrganisationTestHelper;

it('loads the edit page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $avgResponsibleProcessingRecordService = AvgProcessorProcessingRecordService::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->get(AvgProcessorProcessingRecordServiceResource::getUrl('edit', [
            'record' => $avgResponsibleProcessingRecordService,
        ]))
        ->assertSuccessful();
});
