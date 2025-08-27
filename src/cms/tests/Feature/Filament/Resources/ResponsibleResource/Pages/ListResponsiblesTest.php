<?php

declare(strict_types=1);

use App\Filament\Resources\ResponsibleResource\Pages\ListResponsibles;
use App\Models\Responsible;
use Tests\Helpers\Model\OrganisationTestHelper;

it('loads the list page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $responsibles = Responsible::factory()
        ->recycle($organisation)
        ->count(5)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ListResponsibles::class)
        ->assertCanSeeTableRecords($responsibles);
});
