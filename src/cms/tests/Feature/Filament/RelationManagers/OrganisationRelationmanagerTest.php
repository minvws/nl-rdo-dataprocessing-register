<?php

declare(strict_types=1);

use App\Filament\RelationManagers\OrganisationRelationManager;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Models\Organisation;
use App\Models\User;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;

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

it('can search organisations to attach', function (): void {
    $organisation = Organisation::factory()
        ->create(['name' => 'Ministerie van VWS']);
    $user = User::factory()
        ->create();

    $searchResults = $this->asFilamentUser()
        ->createLivewireTestable(OrganisationRelationManager::class, [
            'ownerRecord' => $user,
            'pageClass' => EditUser::class,
        ])
        ->mountTableAction(AttachAction::getDefaultName())
        ->instance()
        ->callSchemaComponentMethod('mountedActionSchema0.recordId', 'getSearchResultsForJs', ['search' => 'VWS']);

    expect($searchResults)
        ->toHaveCount(1)
        ->and($searchResults[0]['label'])->toBe('Ministerie van VWS')
        ->and($searchResults[0]['value'])->toBe($organisation->id->toString());
});

it('can attach an organisation', function (): void {
    $organisation = Organisation::factory()
        ->create();
    $user = User::factory()
        ->create();

    $this->asFilamentUser()
        ->createLivewireTestable(OrganisationRelationManager::class, [
            'ownerRecord' => $user,
            'pageClass' => EditUser::class,
        ])
        ->callTableAction(AttachAction::getDefaultName(), data: [
            'recordId' => [$organisation->id->toString()],
        ])
        ->assertHasNoTableActionErrors();

    expect($user->organisations()->whereKey($organisation->id)->exists())
        ->toBeTrue();
});
