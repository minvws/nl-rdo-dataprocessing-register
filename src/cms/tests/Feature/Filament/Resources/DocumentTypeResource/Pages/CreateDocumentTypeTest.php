<?php

declare(strict_types=1);

use App\Filament\Resources\DocumentTypeResource;
use App\Filament\Resources\DocumentTypeResource\Pages\CreateDocumentType;
use App\Models\DocumentType;

it('loads the create page', function (): void {
    $this->asFilamentUser()
        ->get(DocumentTypeResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create an entry', function (): void {
    $name = fake()->uuid();

    $this->asFilamentUser()
        ->createLivewireTestable(CreateDocumentType::class)
        ->fillForm([
            'name' => $name,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(DocumentType::class, [
        'name' => $name,
    ]);
});
