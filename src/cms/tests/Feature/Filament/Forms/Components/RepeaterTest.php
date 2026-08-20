<?php

declare(strict_types=1);

use App\Filament\Resources\AvgResponsibleProcessingRecordResource\Pages\CreateAvgResponsibleProcessingRecord;
use Tests\Helpers\Model\OrganisationTestHelper;

it('announces an added repeater item to the browser', function (): void {
    $organisation = OrganisationTestHelper::create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(CreateAvgResponsibleProcessingRecord::class)
        ->callFormComponentAction('avgGoals', 'add')
        ->assertDispatched('repeater-item-added', statePath: 'data.avgGoals');
});
