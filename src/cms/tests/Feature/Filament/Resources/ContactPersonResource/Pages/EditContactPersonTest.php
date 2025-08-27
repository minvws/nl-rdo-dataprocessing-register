<?php

declare(strict_types=1);

use App\Filament\Resources\ContactPersonResource;
use App\Models\ContactPerson;
use Tests\Helpers\Model\OrganisationTestHelper;

it('loads the form', function (): void {
    $organisation = OrganisationTestHelper::create();
    $contactPerson = ContactPerson::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->get(ContactPersonResource::getUrl('edit', ['record' => $contactPerson]))
        ->assertSuccessful();
});
