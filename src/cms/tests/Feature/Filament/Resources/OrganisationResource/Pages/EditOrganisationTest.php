<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Filament\Resources\OrganisationResource;
use App\Filament\Resources\OrganisationResource\Pages\EditOrganisation;
use App\Models\Organisation;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->user->assignGlobalRole(Role::FUNCTIONAL_MANAGER);
});

it('loads the edit page', function (): void {
    $this->get(OrganisationResource::getUrl('edit', ['record' => $this->organisation->id]))
        ->assertSuccessful();
});

it('can be saved with a unique slug', function (): void {
    $organisation = Organisation::factory()
        ->create();
    $slug = fake()->uuid();

    livewire(EditOrganisation::class, [
        'record' => $organisation->getRouteKey(),
    ])
        ->fillForm([
            'slug' => $slug,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $organisation->refresh();
    expect($organisation->slug)
        ->toBe($slug);
});

it('cannot save if slug is not unique', function (): void {
    $slug = fake()->uuid();
    Organisation::factory()
        ->create([
            'slug' => $slug,
        ]);
    $organisation = Organisation::factory()
        ->create();

    livewire(EditOrganisation::class, [
        'record' => $organisation->getRouteKey(),
    ])
        ->fillForm([
            'slug' => $slug,
        ])
        ->call('save')
        ->assertHasFormErrors(['slug' => 'unique']);
});

it('can save if number is used on a soft deleted model', function (): void {
    $slug = fake()->uuid();
    Organisation::factory()
        ->create([
            'slug' => $slug,
            'deleted_at' => fake()->dateTime(),
        ]);
    $organisation = Organisation::factory()
        ->create();

    livewire(EditOrganisation::class, [
        'record' => $organisation->getRouteKey(),
    ])
        ->fillForm([
            'slug' => $slug,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $organisation->refresh();
    expect($organisation->slug)
        ->toBe($slug);
});

it('can edit ip-whitelist with permission', function (): void {
    $oldAllowedIps = fake()->word();
    $newAllowedIps = '*.*.*.*';

    $organisation = Organisation::factory()
        ->create([
            'allowed_ips' => $oldAllowedIps,
        ]);

    livewire(EditOrganisation::class, [
        'record' => $organisation->getRouteKey(),
    ])
        ->fillForm([
            'allowed_ips' => $newAllowedIps,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $organisation->refresh();
    expect($organisation->allowed_ips)
        ->toBe($newAllowedIps);
});
