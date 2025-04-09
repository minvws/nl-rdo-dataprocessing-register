<?php

declare(strict_types=1);

use App\Filament\Resources\DocumentTypeResource;
use App\Models\DocumentType;

use function Pest\Livewire\livewire;

it('loads the create page', function (): void {
    $this->get(DocumentTypeResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create an entry', function (): void {
    $name = fake()->uuid();

    livewire(DocumentTypeResource\Pages\CreateDocumentType::class)
        ->fillForm([
            'name' => $name,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(DocumentType::class, [
        'name' => $name,
    ]);
});
