<?php

declare(strict_types=1);

use App\Filament\Resources\DocumentResource;
use App\Filament\Resources\DocumentResource\Pages\EditDocument;
use App\Models\Document;

use function Pest\Livewire\livewire;

it('can load the edit page', function (): void {
    $document = Document::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(DocumentResource::getUrl('edit', ['record' => $document->id]))
        ->assertSuccessful();
});

it('can edit an existing document', function (): void {
    $document = Document::factory()
        ->recycle($this->organisation)
        ->create([
            'expires_at' => null,
            'notify_at' => null,
        ]);
    $name = fake()->word();

    livewire(EditDocument::class, [
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
