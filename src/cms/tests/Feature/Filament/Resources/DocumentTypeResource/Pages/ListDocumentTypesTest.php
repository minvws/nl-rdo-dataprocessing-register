<?php

declare(strict_types=1);

use App\Filament\Resources\DocumentTypeResource\Pages\ListDocumentTypes;
use App\Models\DocumentType;

use function Pest\Livewire\livewire;

it('can load the list resource page', function (): void {
    $records = DocumentType::factory()
        ->recycle($this->organisation)
        ->count(5)
        ->create();

    livewire(ListDocumentTypes::class)
        ->assertCanSeeTableRecords($records);
});
