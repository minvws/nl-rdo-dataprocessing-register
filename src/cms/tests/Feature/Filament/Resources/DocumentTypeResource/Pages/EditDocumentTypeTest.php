<?php

declare(strict_types=1);

use App\Filament\Resources\DocumentTypeResource;
use App\Models\DocumentType;

it('can load the edit page', function (): void {
    $documentType = DocumentType::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(DocumentTypeResource::getUrl('edit', ['record' => $documentType->id]))
        ->assertSuccessful();
});
