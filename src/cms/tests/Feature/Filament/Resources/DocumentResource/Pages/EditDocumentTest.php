<?php

declare(strict_types=1);

use App\Filament\Resources\DocumentResource;
use App\Filament\Resources\DocumentResource\Pages\EditDocument;
use App\Models\Document;
use Tests\Helpers\Model\OrganisationTestHelper;

it('can load the edit page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $document = Document::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->get(DocumentResource::getUrl('edit', ['record' => $document]))
        ->assertSuccessful();
});

it('can edit an existing document', function (): void {
    $organisation = OrganisationTestHelper::create();
    $document = Document::factory()
        ->recycle($organisation)
        ->create([
            'expires_at' => null,
            'notify_at' => null,
        ]);
    $name = fake()->word();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(EditDocument::class, [
            'record' => $document->getRouteKey(),
        ])
        ->fillForm([
            'name' => $name,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $document->refresh();
    expect($document->name)
        ->toBe($name);
});
