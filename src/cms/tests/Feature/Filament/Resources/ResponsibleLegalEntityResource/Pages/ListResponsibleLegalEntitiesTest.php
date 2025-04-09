<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Filament\Resources\ResponsibleLegalEntityResource\Pages\ListResponsibleLegalEnties;
use App\Models\ResponsibleLegalEntity;

use function Pest\Livewire\livewire;

it('loads the list page', function (): void {
    $this->user->assignGlobalRole(Role::FUNCTIONAL_MANAGER);

    $responsibleLegalEntities = ResponsibleLegalEntity::factory()
        ->count(3)
        ->create();

    livewire(ListResponsibleLegalEnties::class)
        ->assertCanSeeTableRecords($responsibleLegalEntities);
});
