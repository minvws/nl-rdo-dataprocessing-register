<?php

declare(strict_types=1);

use App\Filament\RelationManagers\UsersRelationManager;
use App\Filament\Resources\OrganisationResource\Pages\EditOrganisation;
use App\Models\Organisation;
use App\Models\User;

use function Pest\Livewire\livewire;

it('loads the table', function (): void {
    $organisation = Organisation::factory()
        ->has(User::factory()->count(3))
        ->create();

    livewire(UsersRelationManager::class, [
        'ownerRecord' => $organisation,
        'pageClass' => EditOrganisation::class,
    ])->assertCanSeeTableRecords($organisation->users);
});
