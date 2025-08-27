<?php

declare(strict_types=1);

use App\Filament\Resources\ContactPersonResource\Pages\ListContactPersons;
use App\Models\ContactPerson;
use Tests\Helpers\Model\OrganisationTestHelper;

it('loads the list page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $contactPersons = ContactPerson::factory()
        ->recycle($organisation)
        ->count(5)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(ListContactPersons::class)
        ->assertCanSeeTableRecords($contactPersons);
});
