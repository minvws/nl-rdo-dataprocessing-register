<?php

declare(strict_types=1);

use App\Filament\Resources\DocumentResource\Pages\ListDocuments;
use App\Models\Document;

use function Pest\Livewire\livewire;

it('loads the list page', function (): void {
    $document = Document::factory()
        ->recycle($this->organisation)
        ->count(5)
        ->create();

    livewire(ListDocuments::class)
        ->assertCanSeeTableRecords($document);
});
