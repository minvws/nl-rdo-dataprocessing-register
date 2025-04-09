<?php

declare(strict_types=1);

use App\Filament\RelationManagers\UsersRelationManager;
use App\Filament\Resources\OrganisationResource\Pages\EditOrganisation;
use App\Models\Organisation;
use App\Models\User;
use Filament\Tables\Actions\DetachAction;

use function Pest\Livewire\livewire;

it('loads the table', function (): void {
    $user = User::factory()
        ->create();
    $organisation = Organisation::factory()
        ->hasAttached($user)
        ->create();

    livewire(UsersRelationManager::class, [
        'ownerRecord' => $organisation,
        'pageClass' => EditOrganisation::class,
    ])->assertCanSeeTableRecords([$user]);
});

it('does not delete the user if no longer attached to an organisation', function (): void {
    $user = User::factory()
        ->create();
    $organisation = Organisation::factory()
        ->hasAttached($user)
        ->create();

    livewire(UsersRelationManager::class, [
        'ownerRecord' => $organisation,
        'pageClass' => EditOrganisation::class,
    ])
        ->callTableAction(DetachAction::getDefaultName(), $user);

    $user->refresh();

    expect($user->trashed())
        ->toBeFalse();
});

it('also does not delete the user if still attached to another organisation', function (): void {
    $user = User::factory()
        ->create();
    $organisation = Organisation::factory()
        ->hasAttached($user)
        ->create();
    Organisation::factory()
        ->hasAttached($user)
        ->create();

    livewire(UsersRelationManager::class, [
        'ownerRecord' => $organisation,
        'pageClass' => EditOrganisation::class,
    ])
        ->callTableAction(DetachAction::getDefaultName(), $user);

    $user->refresh();

    expect($user->trashed())
        ->toBeFalse();
});
