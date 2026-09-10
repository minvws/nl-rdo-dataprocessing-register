<?php

declare(strict_types=1);

use App\Filament\Resources\OrganisationUserResource;
use App\Models\User;
use Tests\Helpers\Model\OrganisationTestHelper;
use Tests\Helpers\Model\UserTestHelper;

it('does not load the view page for a user from another organisation', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation);

    $otherOrganisation = OrganisationTestHelper::create();
    $record = User::factory()
        ->hasAttached($otherOrganisation)
        ->create();

    $this->asFilamentUser($user)
        ->get(OrganisationUserResource::getUrl('view', ['record' => $record]))
        ->assertNotFound();
});

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
