<?php

declare(strict_types=1);

use App\Filament\Resources\OrganisationResource;
use App\Filament\Resources\OrganisationResource\Pages\CreateOrganisation;
use App\Models\Organisation;
use App\Models\ResponsibleLegalEntity;
use Tests\Helpers\Model\OrganisationTestHelper;
use Tests\Helpers\Model\UserTestHelper;

it('loads the create page', function (): void {
    $this->asFilamentUser()
        ->get(OrganisationResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create as functional manager', function (): void {
    $newOrganisation = Organisation::factory()->make();
    $responsibleLegalEntity = ResponsibleLegalEntity::factory()->create();

    $this->asFilamentUser()
        ->createLivewireTestable(CreateOrganisation::class)
        ->fillForm([
            'name' => $newOrganisation->name,
            'slug' => $newOrganisation->slug,
            'responsible_legal_entity_id' => $responsibleLegalEntity->id,
            'register_entity_number_counter_id' => ucfirst(fake()->randomLetter()),
            'databreach_entity_number_counter_id' => ucfirst(fake()->randomLetter()),
            'public_website_content' => '',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Organisation::class, [
        'name' => $newOrganisation->name,
        'slug' => $newOrganisation->slug,
        'responsible_legal_entity_id' => $responsibleLegalEntity->id,
        'public_website_content' => null,
    ]);
});

it('cannot create without permission', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation);

    $this->withFilamentSession($user, $organisation)
        ->createLivewireTestable(CreateOrganisation::class)
        ->assertForbidden();
});
