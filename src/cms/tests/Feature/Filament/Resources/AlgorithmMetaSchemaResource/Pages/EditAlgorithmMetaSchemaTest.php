<?php

declare(strict_types=1);

use App\Filament\Resources\AlgorithmMetaSchemaResource;
use App\Models\Algorithm\AlgorithmMetaSchema;

it('can load the edit page', function (): void {
    $algorithmMetaSchema = AlgorithmMetaSchema::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(AlgorithmMetaSchemaResource::getUrl('edit', ['record' => $algorithmMetaSchema->id]))
        ->assertSuccessful();
});
