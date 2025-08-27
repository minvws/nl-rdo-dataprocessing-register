<?php

declare(strict_types=1);

use App\Filament\Resources\AvgResponsibleProcessingRecordServiceResource;
use App\Models\Avg\AvgResponsibleProcessingRecordService;
use Tests\Helpers\Model\OrganisationTestHelper;

it('loads the view page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $avgResponsibleProcessingRecordService = AvgResponsibleProcessingRecordService::factory()
        ->recycle($organisation)
        ->create();

    $this
        ->asFilamentOrganisationUser($organisation)
        ->get(AvgResponsibleProcessingRecordServiceResource::getUrl('view', [
            'record' => $avgResponsibleProcessingRecordService,
        ]),)->assertSuccessful();
});
