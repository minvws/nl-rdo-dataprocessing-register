<?php

declare(strict_types=1);

use App\Filament\Resources\SystemResource\Pages\ListSystems;
use App\Models\System;
use Tests\Helpers\Model\OrganisationTestHelper;

it('loads the list page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $systems = System::factory()
        ->recycle($organisation)
        ->count(5)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ListSystems::class)
        ->assertCanSeeTableRecords($systems);
});
