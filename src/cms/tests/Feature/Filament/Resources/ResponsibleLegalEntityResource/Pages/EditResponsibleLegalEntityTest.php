<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Filament\Resources\ResponsibleLegalEntityResource;
use App\Models\ResponsibleLegalEntity;

it('loads the edit page', function (): void {
    $this->user->assignGlobalRole(Role::FUNCTIONAL_MANAGER);

    $responsibleLegalEntity = ResponsibleLegalEntity::factory()
        ->create();

    $this->get(ResponsibleLegalEntityResource::getUrl('edit', ['record' => $responsibleLegalEntity->id]))
        ->assertSuccessful();
});
