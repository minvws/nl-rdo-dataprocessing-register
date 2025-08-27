<?php

declare(strict_types=1);

use App\Filament\Resources\ResponsibleLegalEntityResource\Pages\ListResponsibleLegalEnties;
use App\Models\ResponsibleLegalEntity;
use Tests\Helpers\Model\OrganisationTestHelper;

it('loads the list page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $responsibleLegalEntities = ResponsibleLegalEntity::factory()
        ->recycle($organisation)
        ->count(3)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ListResponsibleLegalEnties::class)
        ->assertCanSeeTableRecords($responsibleLegalEntities);
});
