<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Filament\Resources\UserOrganisationResource;

it('loads the view page', function (): void {
    $this->user->assignGlobalRole(Role::FUNCTIONAL_MANAGER);

    $this->get(UserOrganisationResource::getUrl('view', ['record' => $this->user->id]))
        ->assertSuccessful();
});
