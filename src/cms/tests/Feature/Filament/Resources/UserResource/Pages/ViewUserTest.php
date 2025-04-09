<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Filament\Resources\UserResource;

it('loads the view page', function (): void {
    $this->user->assignGlobalRole(Role::FUNCTIONAL_MANAGER);

    $this->get(UserResource::getUrl('view', ['record' => $this->user->id]))
        ->assertSuccessful();
});
