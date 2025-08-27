<?php

declare(strict_types=1);

use App\Filament\RelationManagers\OrganisationRelationManager;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Models\Organisation;
use App\Models\User;
use Filament\Tables\Actions\DetachAction;

it('loads the table', function (): void {
    $organisation = Organisation::factory()
        ->create();
    $user = User::factory()
        ->hasAttached($organisation)
        ->create();

    $this->asFilamentUser()
        ->createLivewireTestable(OrganisationRelationManager::class, [
            'ownerRecord' => $user,
            'pageClass' => EditUser::class,
        ])
        ->assertCanSeeTableRecords([$organisation]);
});

it('does not delete the organisation if no longer attached to an user', function (): void {
    $organisation = Organisation::factory()
        ->create();
    $user = User::factory()
        ->hasAttached($organisation)
        ->create();

    $this->asFilamentUser()
        ->createLivewireTestable(OrganisationRelationManager::class, [
            'ownerRecord' => $user,
            'pageClass' => EditUser::class,
        ])
        ->callTableAction(DetachAction::getDefaultName(), $organisation);

    $organisation->refresh();

    expect($organisation->trashed())
        ->toBeFalse();
});

it('also does not delete the organisation if still attached to another user', function (): void {
    $organisation = Organisation::factory()
        ->create();
    $user = User::factory()
        ->hasAttached($organisation)
        ->create();
    User::factory()
        ->hasAttached($organisation)
        ->create();

    $this->asFilamentUser()
        ->createLivewireTestable(OrganisationRelationManager::class, [
            'ownerRecord' => $user,
            'pageClass' => EditUser::class,
        ])
        ->callTableAction(DetachAction::getDefaultName(), $organisation);

    $organisation->refresh();

    expect($organisation->trashed())
        ->toBeFalse();
});
