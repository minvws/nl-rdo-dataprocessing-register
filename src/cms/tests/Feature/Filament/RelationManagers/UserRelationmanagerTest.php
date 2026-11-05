<?php

declare(strict_types=1);

use App\Filament\RelationManagers\UsersRelationManager;
use App\Filament\Resources\OrganisationResource\Pages\EditOrganisation;
use App\Models\Organisation;
use App\Models\User;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;

it('loads the table', function (): void {
    $user = User::factory()
        ->create();
    $organisation = Organisation::factory()
        ->hasAttached($user)
        ->create();

    $this->asFilamentUser()
        ->createLivewireTestable(UsersRelationManager::class, [
            'ownerRecord' => $organisation,
            'pageClass' => EditOrganisation::class,
        ])
        ->assertCanSeeTableRecords([$user]);
});

it('does not delete the user if no longer attached to an organisation', function (): void {
    $user = User::factory()
        ->create();
    $organisation = Organisation::factory()
        ->hasAttached($user)
        ->create();

    $this->asFilamentUser()
        ->createLivewireTestable(UsersRelationManager::class, [
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

    $this->asFilamentUser()
        ->createLivewireTestable(UsersRelationManager::class, [
            'ownerRecord' => $organisation,
            'pageClass' => EditOrganisation::class,
        ])
        ->callTableAction(DetachAction::getDefaultName(), $user);

    $user->refresh();

    expect($user->trashed())
        ->toBeFalse();
});

it('can search users to attach', function (): void {
    $user = User::factory()
        ->create(['email' => 'zoekbaar@example.org']);
    $organisation = Organisation::factory()
        ->create();

    $searchResults = $this->asFilamentUser()
        ->createLivewireTestable(UsersRelationManager::class, [
            'ownerRecord' => $organisation,
            'pageClass' => EditOrganisation::class,
        ])
        ->mountTableAction(AttachAction::getDefaultName())
        ->instance()
        ->callSchemaComponentMethod('mountedActionSchema0.recordId', 'getSearchResultsForJs', ['search' => 'zoekbaar']);

    expect($searchResults)
        ->toHaveCount(1)
        ->and($searchResults[0]['label'])->toBe('zoekbaar@example.org')
        ->and($searchResults[0]['value'])->toBe($user->id->toString());
});

it('can attach a user', function (): void {
    $user = User::factory()
        ->create();
    $organisation = Organisation::factory()
        ->create();

    $this->asFilamentUser()
        ->createLivewireTestable(UsersRelationManager::class, [
            'ownerRecord' => $organisation,
            'pageClass' => EditOrganisation::class,
        ])
        ->callTableAction(AttachAction::getDefaultName(), data: [
            'recordId' => [$user->id->toString()],
        ])
        ->assertHasNoTableActionErrors();

    expect($organisation->users()->whereKey($user->id)->exists())
        ->toBeTrue();
});
