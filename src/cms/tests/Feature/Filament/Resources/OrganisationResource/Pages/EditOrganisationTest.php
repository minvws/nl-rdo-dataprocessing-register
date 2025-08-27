<?php

declare(strict_types=1);

use App\Filament\Resources\OrganisationResource;
use App\Filament\Resources\OrganisationResource\Pages\EditOrganisation;
use Tests\Helpers\Model\OrganisationTestHelper;

it('loads the edit page', function (): void {
    $organisation = OrganisationTestHelper::create();

    $this->asFilamentOrganisationUser($organisation)
        ->get(OrganisationResource::getUrl('edit', ['record' => $organisation]))
        ->assertSuccessful();
});

it('can be saved with a unique slug', function (): void {
    $organisation = OrganisationTestHelper::create();
    $slug = fake()->uuid();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(EditOrganisation::class, [
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
    OrganisationTestHelper::create([
        'slug' => $slug,
    ]);
    $organisation = OrganisationTestHelper::create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(EditOrganisation::class, [
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
    OrganisationTestHelper::create([
        'slug' => $slug,
        'deleted_at' => fake()->dateTime(),
    ]);
    $organisation = OrganisationTestHelper::create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(EditOrganisation::class, [
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

    $organisation = OrganisationTestHelper::create([
        'allowed_ips' => $oldAllowedIps,
    ]);

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(EditOrganisation::class, [
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
