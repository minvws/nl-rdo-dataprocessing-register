<?php

declare(strict_types=1);

use App\Filament\Resources\SystemResource;
use App\Filament\Resources\SystemResource\Pages\EditSystem;
use App\Models\System;
use Tests\Helpers\Model\OrganisationTestHelper;

it('loads the edit page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $system = System::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->get(SystemResource::getUrl('edit', ['record' => $system]))
        ->assertSuccessful();
});

it('can not save a system with a non-unique description', function (): void {
    $description = fake()->uuid();

    $organisation = OrganisationTestHelper::create();
    System::factory()
        ->recycle($organisation)
        ->create(['description' => $description]);
    $system = System::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(EditSystem::class, ['record' => $system->id])
        ->fillForm([
            'description' => $description,
        ])
        ->call('save')
        ->assertHasFormErrors(['description' => 'unique']);
});
