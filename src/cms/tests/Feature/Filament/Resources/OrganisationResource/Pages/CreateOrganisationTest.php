<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Filament\Resources\OrganisationResource;
use App\Filament\Resources\OrganisationResource\Pages\CreateOrganisation;
use App\Models\Organisation;
use App\Models\ResponsibleLegalEntity;

use function Pest\Livewire\livewire;

it('loads the create page', function (): void {
    $this->user->assignGlobalRole(Role::FUNCTIONAL_MANAGER);

    $this->get(OrganisationResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create as functional manager', function (): void {
    $this->user->assignGlobalRole(Role::FUNCTIONAL_MANAGER);
    $newOrganisation = Organisation::factory()->make();
    $responsibleLegalEntity = ResponsibleLegalEntity::factory()->create();

    livewire(CreateOrganisation::class)
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

it('cannot create as non-functional manager', function (): void {
    livewire(CreateOrganisation::class)
        ->assertForbidden();
});
