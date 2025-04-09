<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Filament\Resources\OrganisationResource;
use App\Models\Organisation;

it('loads the view page', function (): void {
    $this->user->assignGlobalRole(Role::FUNCTIONAL_MANAGER);
    $organisation = Organisation::factory()
        ->create();

    $this->get(OrganisationResource::getUrl('view', ['record' => $organisation->id]))
        ->assertSuccessful();
});

it('loads the view page when organisation has a poster', function (): void {
    $this->user->assignGlobalRole(Role::FUNCTIONAL_MANAGER);
    $organisation = Organisation::factory()
        ->withPosterImage()
        ->create();

    $this->get(OrganisationResource::getUrl('view', ['record' => $organisation->id]))
        ->assertSuccessful();
});
