<?php

declare(strict_types=1);

use App\Filament\Resources\OrganisationUserResource;
use App\Models\User;
use Tests\Helpers\Model\OrganisationTestHelper;
use Tests\Helpers\Model\UserTestHelper;

it('loads the view page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation);

    $record = User::factory()
        ->hasAttached($organisation)
        ->create();

    $this->asFilamentUser($user)
        ->get(OrganisationUserResource::getUrl('view', ['record' => $record]))
        ->assertSuccessful();
});
