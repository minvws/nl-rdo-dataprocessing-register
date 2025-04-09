<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Filament\Resources\OrganisationResource;

it('loads the list page', function (): void {
    $this->user->assignGlobalRole(Role::FUNCTIONAL_MANAGER);

    $this->get(OrganisationResource::getUrl())
        ->assertSuccessful();
});
