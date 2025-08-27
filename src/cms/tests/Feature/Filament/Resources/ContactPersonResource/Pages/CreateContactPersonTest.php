<?php

declare(strict_types=1);

use App\Filament\Resources\ContactPersonResource;
use App\Filament\Resources\ContactPersonResource\Pages\CreateContactPerson;
use App\Models\ContactPerson;
use App\Models\ContactPersonPosition;
use Tests\Helpers\Model\OrganisationTestHelper;

it('loads the create page', function (): void {
    $this->asFilamentUser()
        ->get(ContactPersonResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create an entry', function (): void {
    $organisation = OrganisationTestHelper::create();
    $contactPersonPosition = ContactPersonPosition::factory()
        ->recycle($organisation)
        ->create([
            'enabled' => true,
        ]);
    $name = fake()->uuid();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(CreateContactPerson::class)
        ->fillForm([
            'name' => $name,
            'contact_person_position_id' => $contactPersonPosition->id,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(ContactPerson::class, [
        'name' => $name,
    ]);
});
