<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Filament\Resources\UserOrganisationResource;

beforeEach(function (): void {
    $this->user->assignGlobalRole(Role::FUNCTIONAL_MANAGER);
});

it('loads the list page', function (): void {
    $this->get(UserOrganisationResource::getUrl())
        ->assertSuccessful();
});
