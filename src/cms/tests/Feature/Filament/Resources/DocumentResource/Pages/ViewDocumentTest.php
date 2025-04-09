<?php

declare(strict_types=1);

use App\Filament\Resources\DocumentResource;
use App\Models\Document;

it('can load the view page', function (): void {
    $document = Document::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(DocumentResource::getUrl('view', ['record' => $document->id]))
        ->assertSuccessful();
});
