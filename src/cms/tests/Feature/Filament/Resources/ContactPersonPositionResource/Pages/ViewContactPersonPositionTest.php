<?php

declare(strict_types=1);

use App\Filament\Resources\ContactPersonPositionResource;
use App\Models\ContactPersonPosition;
use Tests\Helpers\Model\OrganisationTestHelper;

it('loads the view page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $contactPersonPosition = ContactPersonPosition::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->get(ContactPersonPositionResource::getUrl('view', ['record' => $contactPersonPosition]))
        ->assertSuccessful();
});
