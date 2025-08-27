<?php

declare(strict_types=1);

use App\Filament\Resources\SystemResource;
use App\Filament\Resources\SystemResource\Pages\CreateSystem;
use App\Models\Organisation;
use App\Models\System;

it('loads the create page', function (): void {
    $this->asFilamentUser()
        ->get(SystemResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create an entry', function (): void {
    $description = fake()->uuid();

    $this->asFilamentUser()
        ->createLivewireTestable(CreateSystem::class)
        ->fillForm([
            'description' => $description,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(System::class, [
        'description' => $description,
    ]);
});

it('can not create an entry with an existing description within same organisation', function (): void {
    $description = fake()->uuid();

    $organisation = Organisation::factory()->create();
    System::factory()
        ->for($organisation)
        ->create(['description' => $description]);

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(CreateSystem::class)
        ->fillForm([
            'description' => $description,
        ])
        ->call('create')
        ->assertHasFormErrors(['description' => 'unique']);
});

it('can create an entry with an existing description within other organisation', function (): void {
    $description = fake()->uuid();

    System::factory()
        ->create(['description' => $description]);

    $this->asFilamentUser()
        ->createLivewireTestable(CreateSystem::class)
        ->fillForm([
            'description' => $description,
        ])
        ->call('create')
        ->assertHasNoFormErrors(['description' => 'unique']);
});
